<?php

declare(strict_types=1);

namespace App\Services\Payment\Drivers;

use App\Contracts\PaymentGatewayInterface;
use App\Models\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class LemonSqueezyDriver implements PaymentGatewayInterface
{
    protected const BASE_URL = 'https://api.lemonsqueezy.com/v1';

    protected string $apiKey;

    protected string $storeId;

    protected string $webhookSecret;

    public function __construct(PaymentGateway $gateway)
    {
        $creds = $gateway->credentials;
        $this->apiKey = $creds['api_key'] ?? '';
        $this->storeId = $creds['store_id'] ?? '';
        $this->webhookSecret = $creds['webhook_secret'] ?? '';
    }

    /**
     * Create a LemonSqueezy checkout session.
     *
     * @param  string  $orderId  Unique order/merchant order ID
     * @param  int  $amount  Payment amount in smallest currency unit (cents for USD)
     * @param  string  $paymentMethodCode  LemonSqueezy variant ID
     * @param  array  $params  Additional params: email, customerName, productDetails, redirectUrl, etc.
     * @return array{reference: string, vaNumber: ?string, qrString: ?string, paymentUrl: ?string, amount: int, statusCode: string}
     */
    public function createTransaction(string $orderId, int $amount, string $paymentMethodCode, array $params = []): array
    {
        $callbackUrl = $params['callbackUrl'] ?? route('payment.callback', ['gateway' => 'lemonsqueezy']);
        $returnUrl = $params['returnUrl'] ?? route('payment.return');

        $payload = [
            'data' => [
                'type' => 'checkouts',
                'attributes' => [
                    'custom_price' => $amount, // in cents
                    'product_options' => [
                        'redirect_url' => $returnUrl,
                        'receipt_button_text' => 'Go to Dashboard',
                    ],
                    'checkout_data' => [
                        'email' => $params['email'] ?? null,
                        'name' => $params['customerVaName'] ?? $params['customerName'] ?? null,
                        'custom' => [
                            'order_id' => $orderId,
                        ],
                    ],
                ],
                'relationships' => [
                    'store' => [
                        'data' => [
                            'type' => 'stores',
                            'id' => $this->storeId,
                        ],
                    ],
                    'variant' => [
                        'data' => [
                            'type' => 'variants',
                            'id' => $paymentMethodCode, // variant_id
                        ],
                    ],
                ],
            ],
        ];

        try {
            $response = $this->apiRequest('POST', '/checkouts', $payload);

            $checkoutUrl = $response['data']['attributes']['url'] ?? null;
            $checkoutId = $response['data']['id'] ?? '';

            if (! $checkoutUrl) {
                Log::error('LemonSqueezy checkout creation failed: no URL in response', [
                    'orderId' => $orderId,
                    'response' => $response,
                ]);
                throw new RuntimeException('LemonSqueezy checkout creation failed: no checkout URL returned');
            }

            return [
                'reference' => $checkoutId,
                'vaNumber' => null,
                'qrString' => null,
                'paymentUrl' => $checkoutUrl,
                'amount' => $amount,
                'statusCode' => '00', // normalized success
                'raw' => $response,
            ];
        } catch (RuntimeException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('LemonSqueezy createTransaction exception', [
                'orderId' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw new RuntimeException('LemonSqueezy payment creation failed: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Check subscription/order status via LemonSqueezy API.
     */
    public function checkTransactionStatus(string $orderId): array
    {
        try {
            // orderId here stores the LemonSqueezy order ID or subscription ID
            $response = $this->apiRequest('GET', "/orders/{$orderId}");

            $attrs = $response['data']['attributes'] ?? [];

            $statusCode = match ($attrs['status'] ?? '') {
                'paid' => '00',
                'pending' => '01',
                'refunded' => '02',
                default => '99',
            };

            return [
                'merchantOrderId' => $attrs['identifier'] ?? $orderId,
                'reference' => $response['data']['id'] ?? '',
                'amount' => (int) (($attrs['total'] ?? 0)),
                'statusCode' => $statusCode,
                'statusMessage' => $attrs['status'] ?? 'unknown',
                'raw' => $response,
            ];
        } catch (\Throwable $e) {
            Log::error('LemonSqueezy checkTransactionStatus exception', [
                'orderId' => $orderId,
                'error' => $e->getMessage(),
            ]);
            throw new RuntimeException('LemonSqueezy status check failed: '.$e->getMessage(), 0, $e);
        }
    }

    /**
     * Verify webhook signature using HMAC SHA-256.
     */
    public function verifyCallback(Request $request): bool
    {
        $signature = $request->header('X-Signature', '');
        $payload = $request->getContent();

        if (empty($signature) || empty($this->webhookSecret)) {
            return false;
        }

        $computedHash = hash_hmac('sha256', $payload, $this->webhookSecret);

        return hash_equals($computedHash, $signature);
    }

    /**
     * Parse LemonSqueezy webhook payload into normalized structure.
     */
    public function parseCallback(Request $request): array
    {
        $data = $request->json()->all();
        $meta = $data['meta'] ?? [];
        $eventName = $meta['event_name'] ?? '';
        $attrs = $data['data']['attributes'] ?? [];
        $customData = $meta['custom_data'] ?? $attrs['first_order_item']['custom_data'] ?? [];

        // Extract the order_id we passed in checkout_data.custom
        $orderId = $customData['order_id'] ?? '';

        // Determine result code based on event
        $resultCode = match ($eventName) {
            'order_created' => ($attrs['status'] ?? '') === 'paid' ? '00' : '01',
            'subscription_created' => '00',
            'subscription_updated' => '00',
            'subscription_payment_success' => '00',
            'subscription_payment_failed' => '01',
            'order_refunded' => '02',
            default => '99',
        };

        return [
            'orderId' => $orderId,
            'reference' => (string) ($data['data']['id'] ?? ''),
            'amount' => (int) ($attrs['total'] ?? 0),
            'resultCode' => $resultCode,
            'paymentCode' => $eventName,
            'subscriptionId' => (string) ($attrs['subscription_id'] ?? $data['data']['id'] ?? ''),
            'customerId' => (string) ($attrs['customer_id'] ?? ''),
            'raw' => $data,
        ];
    }

    /**
     * LemonSqueezy doesn't have a traditional "get payment methods" endpoint.
     * Return empty array — payment method selection happens on the checkout page.
     */
    public function getPaymentMethods(int $amount): array
    {
        return [];
    }

    /**
     * Make an API request to LemonSqueezy.
     */
    protected function apiRequest(string $method, string $endpoint, array $data = []): array
    {
        $url = self::BASE_URL.'/'.ltrim($endpoint, '/');

        $request = Http::withHeaders([
            'Authorization' => "Bearer {$this->apiKey}",
            'Accept' => 'application/vnd.api+json',
            'Content-Type' => 'application/vnd.api+json',
        ]);

        $response = match (strtoupper($method)) {
            'GET' => $request->get($url, $data),
            'POST' => $request->post($url, $data),
            'PATCH' => $request->patch($url, $data),
            'DELETE' => $request->delete($url),
            default => throw new RuntimeException("Unsupported HTTP method: {$method}"),
        };

        if ($response->failed()) {
            Log::error('LemonSqueezy API error', [
                'method' => $method,
                'url' => $url,
                'status' => $response->status(),
                'body' => $response->json(),
            ]);
            throw new RuntimeException("LemonSqueezy API error ({$response->status()}): ".$response->body());
        }

        return $response->json() ?? [];
    }
}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 3.5cm 2cm 2cm 2cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: 3cm;
            padding: 0.6cm 2cm 0 2cm;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-content { width: 100%; height: 100%; }
        .logo-container { float: left; width: 50%; }
        .logo-container img { max-height: 50px; max-width: 200px; }
        .logo-text {
            font-size: 22px;
            font-weight: bold;
            color: #2563eb;
        }
        .header-right {
            float: right;
            width: 45%;
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .header-right .doc-type {
            font-size: 18px;
            font-weight: bold;
            color: #111;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.5cm;
            padding: 10px 2cm;
            background-color: #f9fafb;
            color: #666;
            font-size: 10px;
            line-height: 1.2;
            text-align: center;
        }

        h1, h2, h3 { color: #111; margin: 0 0 8px 0; }

        .row::after { content: ""; clear: both; display: table; }
        .col-6 { float: left; width: 50%; box-sizing: border-box; }

        .meta-box {
            background-color: #f9fafb;
            padding: 14px;
            border-radius: 5px;
            margin-bottom: 16px;
        }
        .meta-title {
            font-size: 10px;
            text-transform: uppercase;
            color: #6b7280;
            margin-bottom: 6px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-danger  { background: #fee2e2; color: #991b1b; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-gray    { background: #f3f4f6; color: #1f2937; }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
        }
        table.items th {
            background-color: #2563eb;
            color: white;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            font-size: 11px;
        }
        table.items td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table.items td.right { text-align: right; }
        table.items th.right { text-align: right; }

        table.totals {
            width: 50%;
            float: right;
            border-collapse: collapse;
            margin-top: 4px;
        }
        table.totals td {
            padding: 6px 10px;
        }
        table.totals td.label { color: #6b7280; text-align: right; }
        table.totals td.value { text-align: right; }
        table.totals tr.total td {
            border-top: 2px solid #111;
            font-weight: bold;
            font-size: 14px;
            color: #111;
            padding-top: 10px;
        }

        .mb-2 { margin-bottom: 8px; }
        .mb-4 { margin-bottom: 16px; }
        .mt-4 { margin-top: 16px; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .text-muted { color: #6b7280; }
        .small { font-size: 10px; }
        .mono { font-family: 'DejaVu Sans Mono', monospace; }

        .payment-box {
            background-color: #f0f9ff;
            border-left: 3px solid #2563eb;
            padding: 12px 14px;
            margin-top: 20px;
            font-size: 11px;
        }
        .payment-box .label { color: #6b7280; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
    </style>
</head>
<body>
    @php
        $statusEnum = $invoice->status;
        $statusValue = $statusEnum instanceof \App\Enums\SaasInvoiceStatus
            ? $statusEnum->value
            : (string) $statusEnum;
        $statusBadge = match ($statusValue) {
            'paid' => ['class' => 'badge-success', 'label' => 'LUNAS'],
            'pending' => ['class' => 'badge-warning', 'label' => 'MENUNGGU PEMBAYARAN'],
            'overdue' => ['class' => 'badge-danger', 'label' => 'JATUH TEMPO'],
            'cancelled' => ['class' => 'badge-gray', 'label' => 'DIBATALKAN'],
            default => ['class' => 'badge-gray', 'label' => strtoupper($statusValue)],
        };
        $sub = $invoice->tenantSubscription;
        $plan = $sub?->subscriptionPlan;
        $brandName = $siteName ?: 'Zewalo App';
    @endphp

    <header>
        <div class="header-content">
            <div class="logo-container">
                @if ($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $brandName }}">
                @else
                    <div class="logo-text">{{ $brandName }}</div>
                @endif
            </div>
            <div class="header-right">
                <div class="doc-type">INVOICE</div>
                <div><strong>{{ $invoice->invoice_number }}</strong></div>
                <div>{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</div>
            </div>
        </div>
    </header>

    <footer>
        Terima kasih atas kepercayaan Anda menggunakan {{ $brandName }}.
        <br>
        <span class="small">Invoice ini dibuat secara otomatis oleh sistem.</span>
    </footer>

    <main>
        <div class="row mb-4">
            <div class="col-6" style="padding-right: 10px;">
                <div class="meta-box">
                    <div class="meta-title">Detail Invoice</div>
                    <p style="margin: 2px 0;"><strong>No. Invoice:</strong> {{ $invoice->invoice_number }}</p>
                    <p style="margin: 2px 0;"><strong>Tanggal Terbit:</strong> {{ $invoice->issued_at?->format('d M Y') ?? '—' }}</p>
                    <p style="margin: 2px 0;"><strong>Jatuh Tempo:</strong> {{ $invoice->due_at?->format('d M Y') ?? '—' }}</p>
                    <p style="margin: 6px 0 2px 0;">
                        <strong>Status:</strong>
                        <span class="badge {{ $statusBadge['class'] }}">{{ $statusBadge['label'] }}</span>
                    </p>
                </div>
            </div>
            <div class="col-6" style="padding-left: 10px;">
                <div class="meta-box">
                    <div class="meta-title">Ditagihkan Kepada</div>
                    <p style="margin: 2px 0;"><strong>{{ $invoice->tenant?->name ?? '—' }}</strong></p>
                    @if ($invoice->tenant?->email ?? null)
                        <p style="margin: 2px 0;">{{ $invoice->tenant->email }}</p>
                    @endif
                    @if ($invoice->tenant?->phone ?? null)
                        <p style="margin: 2px 0;">{{ $invoice->tenant->phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th style="width: 70%;">Deskripsi</th>
                    <th class="right" style="width: 30%;">Jumlah</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong>Langganan {{ $plan?->name ?? 'Zewalo' }}</strong>
                        @if ($sub)
                            <br>
                            <span class="text-muted small">
                                Siklus: {{ ucfirst($sub->billing_cycle ?? '—') }}
                                @if ($sub->started_at && $sub->ends_at)
                                    · Periode: {{ $sub->started_at->format('d M Y') }} – {{ $sub->ends_at->format('d M Y') }}
                                @endif
                            </span>
                        @endif
                    </td>
                    <td class="right">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div class="clearfix">
            <table class="totals">
                <tr>
                    <td class="label">Subtotal</td>
                    <td class="value">Rp {{ number_format($invoice->amount, 0, ',', '.') }}</td>
                </tr>
                @if ((float) $invoice->tax > 0)
                    <tr>
                        <td class="label">Pajak</td>
                        <td class="value">Rp {{ number_format($invoice->tax, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="total">
                    <td class="label">Total</td>
                    <td class="value">Rp {{ number_format($invoice->total, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>

        @if ($invoice->isPaid())
            <div class="payment-box clearfix">
                <div class="label">Informasi Pembayaran</div>
                <p style="margin: 6px 0;">
                    <strong>Metode:</strong> {{ $invoice->paymentMethod?->display_name ?? $invoice->payment_method ?? '—' }}
                </p>
                @if ($invoice->payment_reference || $invoice->gateway_reference_id)
                    <p style="margin: 4px 0;">
                        <strong>Referensi:</strong>
                        <span class="mono small">{{ $invoice->payment_reference ?? $invoice->gateway_reference_id }}</span>
                    </p>
                @endif
                <p style="margin: 4px 0;">
                    <strong>Dibayar pada:</strong> {{ $invoice->paid_at?->format('d M Y H:i') ?? '—' }}
                </p>
            </div>
        @else
            <div class="payment-box clearfix">
                <div class="label">Catatan Pembayaran</div>
                <p style="margin: 6px 0;">
                    Mohon lakukan pembayaran sebelum
                    <strong>{{ $invoice->due_at?->format('d M Y') ?? '—' }}</strong>
                    untuk menghindari penangguhan layanan.
                </p>
                <p class="small text-muted" style="margin: 4px 0;">
                    Pembayaran dapat dilakukan melalui halaman Subscription &amp; Billing pada panel admin Anda.
                </p>
            </div>
        @endif
    </main>
</body>
</html>

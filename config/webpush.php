<?php

return [
    // Platform-wide VAPID identity (set once in the central environment). Web push
    // endpoints are provider-hosted and origin-agnostic, so one keypair serves all
    // tenants; only the push_subscriptions rows are per-tenant.
    'vapid' => [
        'subject' => env('VAPID_SUBJECT', 'mailto:admin@example.com'),
        'public_key' => env('VAPID_PUBLIC_KEY'),
        'private_key' => env('VAPID_PRIVATE_KEY'),
    ],
    'ttl' => 2419200,
    'urgency' => 'normal',
    'topic' => null,
];

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    @php
        $pdfSettings = \App\Filament\Central\Pages\InvoicePdfSettings::loadSettings();
        $primary = $pdfSettings['invoice_pdf_primary_color'];
        $textColor = $pdfSettings['invoice_pdf_text_color'];
        $muted = $pdfSettings['invoice_pdf_muted_color'];
        $accentBg = $pdfSettings['invoice_pdf_accent_bg'];
        $fontSize = (int) $pdfSettings['invoice_pdf_font_size'];
        $showLogo = (bool) $pdfSettings['invoice_pdf_show_logo'];
        $showPlanDesc = (bool) $pdfSettings['invoice_pdf_show_plan_description'];
        $showPlanFeatures = (bool) $pdfSettings['invoice_pdf_show_plan_features'];
        $headerNote = trim($pdfSettings['invoice_pdf_header_note'] ?? '');
        $footerText = trim($pdfSettings['invoice_pdf_footer_text'] ?? '');
        $termsText = $pdfSettings['invoice_pdf_terms_text'] ?? '';

        // Dynamic header height: count lines in header note, allocate space accordingly.
        // Base header (logo + spacing) = 2.0cm. Each note line ≈ 0.35cm.
        $headerNoteLines = $headerNote === '' ? 0 : (substr_count($headerNote, "\n") + 1);
        $baseHeaderCm = 2.0;
        $extraHeaderCm = $headerNoteLines > 0 ? min($headerNoteLines * 0.35, 1.8) : 0;
        $headerHeightCm = $baseHeaderCm + $extraHeaderCm;
        $bodyTopMarginCm = $headerHeightCm + 0.6; // breathing room between header & content
    @endphp
    <style>
        @page {
            margin: 0cm 0cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: {{ $fontSize }}px;
            line-height: 1.5;
            color: {{ $textColor }};
            margin: {{ $bodyTopMarginCm }}cm 1.8cm 2.2cm 1.8cm;
        }

        header {
            position: fixed;
            top: 0cm;
            left: 0cm;
            right: 0cm;
            height: {{ $headerHeightCm }}cm;
            padding: 0.45cm 1.8cm 0.3cm 1.8cm;
            background-color: white;
            border-bottom: 1px solid #e5e7eb;
        }

        .header-content { width: 100%; height: 100%; }
        .logo-container { float: left; width: 50%; }
        .logo-container img { max-height: 36px; max-width: 160px; }
        .logo-text {
            font-size: 16px;
            font-weight: bold;
            color: {{ $primary }};
            letter-spacing: 0.3px;
        }
        .header-note {
            margin-top: 4px;
            font-size: 9px;
            color: {{ $muted }};
            line-height: 1.3;
        }
        .header-right {
            float: right;
            width: 45%;
            text-align: right;
            font-size: 9px;
            color: {{ $muted }};
        }
        .header-right .doc-type {
            font-size: 13px;
            font-weight: bold;
            color: {{ $textColor }};
            letter-spacing: 1px;
            margin-bottom: 2px;
        }
        .header-right .doc-no {
            font-size: 10px;
            color: {{ $textColor }};
            font-weight: bold;
        }

        footer {
            position: fixed;
            bottom: 0cm;
            left: 0cm;
            right: 0cm;
            height: 1.4cm;
            padding: 8px 1.8cm;
            background-color: {{ $accentBg }};
            color: {{ $muted }};
            font-size: 9px;
            line-height: 1.3;
            text-align: center;
        }

        h1, h2, h3 { color: {{ $textColor }}; margin: 0 0 8px 0; }

        .row::after { content: ""; clear: both; display: table; }
        .col-6 { float: left; width: 50%; box-sizing: border-box; }

        .meta-box {
            background-color: {{ $accentBg }};
            padding: 12px 14px;
            border-radius: 5px;
            margin-bottom: 16px;
        }
        .meta-title {
            font-size: 9px;
            text-transform: uppercase;
            color: {{ $muted }};
            margin-bottom: 6px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
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
            margin: 8px 0 4px 0;
        }
        table.items th {
            background-color: {{ $primary }};
            color: white;
            padding: 9px 10px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
        }
        table.items td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            vertical-align: top;
        }
        table.items td.right { text-align: right; }
        table.items th.right { text-align: right; }

        .plan-name { font-weight: bold; color: {{ $textColor }}; font-size: {{ $fontSize }}px; }
        .plan-meta { color: {{ $muted }}; font-size: 10px; margin-top: 3px; }
        .plan-desc { color: {{ $textColor }}; font-size: 10px; margin-top: 6px; line-height: 1.45; }
        .plan-features {
            margin: 8px 0 0 0;
            padding: 0 0 0 14px;
            color: {{ $muted }};
            font-size: 9.5px;
            line-height: 1.5;
        }
        .plan-features li { margin-bottom: 1px; }

        table.totals {
            width: 50%;
            float: right;
            border-collapse: collapse;
            margin-top: 4px;
        }
        table.totals td {
            padding: 5px 10px;
            font-size: {{ $fontSize - 1 }}px;
        }
        table.totals td.label { color: {{ $muted }}; text-align: right; }
        table.totals td.value { text-align: right; color: {{ $textColor }}; }
        table.totals tr.total td {
            border-top: 2px solid {{ $textColor }};
            font-weight: bold;
            font-size: 13px;
            color: {{ $textColor }};
            padding-top: 9px;
        }

        .clearfix::after { content: ""; clear: both; display: table; }
        .text-muted { color: {{ $muted }}; }
        .small { font-size: 9px; }
        .mono { font-family: 'DejaVu Sans Mono', monospace; }

        .payment-box {
            background-color: {{ $accentBg }};
            border-left: 3px solid {{ $primary }};
            padding: 11px 14px;
            margin-top: 18px;
            font-size: 10px;
        }
        .payment-box .label { color: {{ $muted }}; font-size: 9px; text-transform: uppercase; letter-spacing: 0.5px; }
        .payment-box p { margin: 4px 0; }

        .terms {
            margin-top: 18px;
            padding: 10px 12px;
            border: 1px dashed #d1d5db;
            border-radius: 4px;
            font-size: 9px;
            color: {{ $muted }};
            line-height: 1.5;
        }
        .terms .terms-title {
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
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

        $cycleLabel = match ($sub?->billing_cycle) {
            'monthly' => 'Bulanan',
            'yearly' => 'Tahunan',
            default => $sub?->billing_cycle ?? '—',
        };

        $features = is_array($plan?->features ?? null) ? $plan->features : [];
    @endphp

    <header>
        <div class="header-content">
            <div class="logo-container">
                @if ($showLogo && $logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $brandName }}">
                @else
                    <div class="logo-text">{{ $brandName }}</div>
                @endif

                @if ($headerNote)
                    <div class="header-note">{!! nl2br(e($headerNote)) !!}</div>
                @endif
            </div>
            <div class="header-right">
                <div class="doc-type">INVOICE</div>
                <div class="doc-no">{{ $invoice->invoice_number }}</div>
                <div>{{ $invoice->issued_at?->format('d M Y') ?? '—' }}</div>
            </div>
        </div>
    </header>

    <footer>
@if ($footerText)
<div style="text-align: center;">{!! nl2br(e($footerText)) !!}</div>
@endif
<div class="small" style="text-align: center;">Invoice ini dibuat secara otomatis oleh sistem {{ $brandName }}.</div>
    </footer>

    <main>
        <div class="row" style="margin-bottom: 14px;">
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
                        <div class="plan-name">
                            Langganan {{ $brandName }} — {{ $plan?->name ?? 'Paket Standar' }}
                        </div>
                        <div class="plan-meta">
                            Siklus: <strong>{{ $cycleLabel }}</strong>
                            @if ($sub?->started_at && $sub?->ends_at)
                                · Periode: {{ $sub->started_at->format('d M Y') }} – {{ $sub->ends_at->format('d M Y') }}
                            @endif
                        </div>

                        @if ($showPlanDesc && $plan?->description)
                            <div class="plan-desc">{{ $plan->description }}</div>
                        @endif

                        @if ($showPlanFeatures && $plan)
                            @php
                                $limits = array_filter([
                                    $plan->max_users ? 'Maks. ' . $plan->max_users . ' pengguna' : null,
                                    $plan->max_products ? 'Maks. ' . $plan->max_products . ' produk' : null,
                                    $plan->max_storage_mb ? 'Storage ' . ($plan->max_storage_mb >= 1024 ? round($plan->max_storage_mb / 1024, 1) . ' GB' : $plan->max_storage_mb . ' MB') : null,
                                    $plan->max_rental_transactions_per_month ? 'Maks. ' . $plan->max_rental_transactions_per_month . ' transaksi/bulan' : null,
                                    $plan->max_domains ? 'Maks. ' . $plan->max_domains . ' domain' : null,
                                ]);
                                $allItems = array_merge($limits, $features);
                            @endphp
                            @if (count($allItems) > 0)
                                <ul class="plan-features">
                                    @foreach ($allItems as $item)
                                        <li>{{ is_string($item) ? $item : json_encode($item) }}</li>
                                    @endforeach
                                </ul>
                            @endif
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
                <p>
                    <strong>Metode:</strong> {{ $invoice->paymentMethod?->display_name ?? $invoice->payment_method ?? '—' }}
                </p>
                @if ($invoice->payment_reference || $invoice->gateway_reference_id)
                    <p>
                        <strong>Referensi:</strong>
                        <span class="mono small">{{ $invoice->payment_reference ?? $invoice->gateway_reference_id }}</span>
                    </p>
                @endif
                <p>
                    <strong>Dibayar pada:</strong> {{ $invoice->paid_at?->format('d M Y H:i') ?? '—' }}
                </p>
            </div>
        @else
            <div class="payment-box clearfix">
                <div class="label">Catatan Pembayaran</div>
                <p>
                    Mohon lakukan pembayaran sebelum
                    <strong>{{ $invoice->due_at?->format('d M Y') ?? '—' }}</strong>
                    untuk menghindari penangguhan layanan.
                </p>
                <p class="small text-muted">
                    Pembayaran dapat dilakukan melalui halaman Subscription &amp; Billing pada panel admin Anda.
                </p>
            </div>
        @endif

        @if ($termsText)
            <div class="terms">
                <div class="terms-title">Syarat &amp; Ketentuan</div>
                {!! nl2br(e($termsText)) !!}
            </div>
        @endif
    </main>
</body>
</html>

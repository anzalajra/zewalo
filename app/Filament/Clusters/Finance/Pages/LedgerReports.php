<?php

namespace App\Filament\Clusters\Finance\Pages;

use App\Filament\Clusters\Finance\FinanceCluster;
use App\Models\Account;
use App\Models\Setting;
use App\Services\AgingReportService;
use App\Services\LedgerReportService;
use App\Services\TaxReportService;
use BackedEnum;
use Carbon\Carbon;
use Filament\Pages\Page;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Financial statements derived from the GENERAL LEDGER (posted journal entries) — the
 * double-entry counterpart to the operational FinancialReports. Only meaningful in
 * Advanced (double-entry) accounting mode, so it is hidden otherwise.
 *
 * Covers Trial Balance, Income Statement, Balance Sheet, AR/AP Aging, a per-account
 * General Ledger drill-down, and the PPN Keluaran / PPh 23 tax recaps (with CSV export).
 */
class LedgerReports extends Page
{
    protected static ?string $cluster = FinanceCluster::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $navigationLabel = 'Laporan GL';

    protected static ?string $title = 'Laporan Keuangan (General Ledger)';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.clusters.finance.pages.ledger-reports';

    #[Url]
    public ?string $startDate = null;

    #[Url]
    public ?string $endDate = null;

    #[Url]
    public ?int $ledgerAccountId = null;

    public static function shouldRegisterNavigation(): bool
    {
        return Setting::get('finance_mode', 'simple') === 'advanced';
    }

    public function mount(): void
    {
        $this->startDate = $this->startDate ?: now()->startOfYear()->toDateString();
        $this->endDate = $this->endDate ?: now()->endOfMonth()->toDateString();
    }

    #[Computed]
    public function trialBalance(): array
    {
        // Cumulative balances as of the period-end date (reconciles with the Balance Sheet).
        return LedgerReportService::trialBalance(null, $this->endDate);
    }

    #[Computed]
    public function incomeStatement(): array
    {
        return LedgerReportService::incomeStatement($this->startDate, $this->endDate);
    }

    #[Computed]
    public function balanceSheet(): array
    {
        return LedgerReportService::balanceSheet($this->endDate);
    }

    #[Computed]
    public function arAging(): array
    {
        return AgingReportService::receivables($this->endDate);
    }

    #[Computed]
    public function apAging(): array
    {
        return AgingReportService::payables($this->endDate);
    }

    public function accountOptions(): array
    {
        return Account::query()
            ->orderBy('code')
            ->get()
            ->mapWithKeys(fn ($a) => [$a->id => "{$a->code} · {$a->name}"])
            ->toArray();
    }

    #[Computed]
    public function generalLedger(): ?array
    {
        if (! $this->ledgerAccountId) {
            return null;
        }

        return LedgerReportService::generalLedger(
            (int) $this->ledgerAccountId,
            $this->startDate,
            $this->endDate,
        );
    }

    #[Computed]
    public function taxRecap(): array
    {
        return TaxReportService::outputTaxLines(
            Carbon::parse($this->startDate),
            Carbon::parse($this->endDate),
        );
    }

    #[Computed]
    public function pph23Recap(): array
    {
        return TaxReportService::withholdingLines(
            Carbon::parse($this->startDate),
            Carbon::parse($this->endDate),
        );
    }

    /** Aging bucket labels for the blade. */
    public function bucketLabels(): array
    {
        return AgingReportService::BUCKET_LABELS;
    }

    /** Download the PPN Keluaran recap as CSV (Faktur Pajak / e-Faktur precursor). */
    public function exportTaxCsv(): StreamedResponse
    {
        $recap = TaxReportService::outputTaxLines(
            Carbon::parse($this->startDate),
            Carbon::parse($this->endDate),
        );

        $filename = 'ppn-keluaran-'.$this->startDate.'-'.$this->endDate.'.csv';

        return response()->streamDownload(function () use ($recap) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tanggal', 'No Invoice', 'No Faktur Pajak', 'Pelanggan', 'NPWP', 'DPP', 'PPN']);
            foreach ($recap['lines'] as $line) {
                fputcsv($out, [
                    $line['date'],
                    $line['invoice_number'],
                    $line['tax_invoice_number'],
                    $line['customer'],
                    $line['npwp'],
                    $line['dpp'],
                    $line['ppn'],
                ]);
            }
            fputcsv($out, ['', '', '', '', 'TOTAL', $recap['total_dpp'], $recap['total_ppn']]);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /** Download the PPh 23 withholding recap as CSV (rekap kredit pajak). */
    public function exportWithholdingCsv(): StreamedResponse
    {
        $recap = TaxReportService::withholdingLines(
            Carbon::parse($this->startDate),
            Carbon::parse($this->endDate),
        );

        $filename = 'pph23-kredit-'.$this->startDate.'-'.$this->endDate.'.csv';

        return response()->streamDownload(function () use ($recap) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tanggal', 'No Invoice', 'No Bukti Potong', 'Pelanggan', 'NPWP', 'DPP', 'PPh 23']);
            foreach ($recap['lines'] as $line) {
                fputcsv($out, [
                    $line['date'],
                    $line['invoice_number'],
                    $line['bukti_potong'],
                    $line['customer'],
                    $line['npwp'],
                    $line['dpp'],
                    $line['pph23'],
                ]);
            }
            fputcsv($out, ['', '', '', '', 'TOTAL', $recap['total_dpp'], $recap['total_pph23']]);
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}

<?php

namespace App\Filament\Actions;

use App\Models\FinanceAccount;
use App\Models\FinanceTransaction;
use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

/**
 * Single source of truth for recording an invoice payment.
 *
 * Records an income FinanceTransaction linked to the invoice and re-runs
 * Invoice::recalculate() (which derives paid_amount + status). The GL journal is
 * posted automatically by JournalService::syncFromTransaction — simple mode uses the
 * generic Dr-Cash/Cr-contra path, advanced mode routes 'Invoice Payment' to the
 * canonical engine (Dr Kas / Cr Piutang). No explicit canonical call here — that
 * would double-post in advanced mode.
 *
 * PPh 23: when the customer withholds PPh 23, we store the withheld amount on the
 * transaction's `tax_amount`. The switchover router (postFromTransaction) reads it and
 * posts the extra Dr 1-1500 (PPh 23 Dibayar Dimuka) / Cr Piutang leg, so the full
 * receivable is settled by net cash + tax credit — again without a double-post.
 */
class RecordPaymentAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'record_payment';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Record Payment')
            ->icon('heroicon-o-banknotes')
            ->color('success')
            ->visible(fn (Invoice $record): bool => $record->status !== Invoice::STATUS_PAID
                && $record->status !== 'cancelled'
                && $record->balance > 0)
            ->form(fn (Invoice $record): array => [
                Select::make('finance_account_id')
                    ->label('Deposit To Account')
                    ->options(FinanceAccount::where('is_active', true)->pluck('name', 'id'))
                    ->required(),
                TextInput::make('amount')
                    ->label('Amount')
                    ->required()
                    ->numeric()
                    ->prefix('Rp')
                    ->default($record->balance)
                    ->maxValue($record->balance),
                DatePicker::make('date')
                    ->label('Payment Date')
                    ->default(now())
                    ->required(),
                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'Cash' => 'Cash',
                        'Transfer' => 'Bank Transfer',
                        'QRIS' => 'QRIS',
                        'Credit Card' => 'Credit Card',
                    ])
                    ->required(),
                Toggle::make('pph23_withheld')
                    ->label('Customer withholds PPh 23?')
                    ->helperText('Corporate customers may withhold PPh 23 (usually 2% of DPP). The withheld amount is recorded as a prepaid-tax credit and settles the receivable alongside the cash paid.')
                    ->live(),
                TextInput::make('pph23_amount')
                    ->label('PPh 23 Withheld')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(fn (Invoice $record): float => round((float) ($record->tax_base ?: $record->subtotal) * 0.02, 2))
                    ->visible(fn ($get): bool => (bool) $get('pph23_withheld')),
                TextInput::make('pph23_bukti_potong_number')
                    ->label('No. Bukti Potong')
                    ->visible(fn ($get): bool => (bool) $get('pph23_withheld')),
                Textarea::make('notes')
                    ->label('Notes'),
            ])
            ->action(function (Invoice $record, array $data): void {
                // PPh 23 withheld by the customer (recorded as a prepaid-tax credit).
                $withholding = ! empty($data['pph23_withheld']) ? (float) ($data['pph23_amount'] ?? 0) : 0.0;

                $transaction = new FinanceTransaction([
                    'finance_account_id' => $data['finance_account_id'],
                    'user_id' => Auth::id(),
                    'type' => FinanceTransaction::TYPE_INCOME,
                    'amount' => $data['amount'],
                    'date' => $data['date'],
                    'category' => 'Invoice Payment',
                    'description' => 'Payment for Invoice #'.$record->number,
                    'payment_method' => $data['payment_method'],
                    'notes' => $data['notes'] ?? null,
                    // Carries the withheld PPh 23 for the switchover router (advanced mode);
                    // ignored by the generic simple-mode posting path.
                    'tax_amount' => $withholding > 0 ? $withholding : null,
                ]);
                $transaction->reference()->associate($record);
                $transaction->save();

                if ($withholding > 0) {
                    $record->pph23_withheld = true;
                    $record->pph23_amount = (float) $record->pph23_amount + $withholding;
                    $record->pph23_bukti_potong_number = $data['pph23_bukti_potong_number'] ?? $record->pph23_bukti_potong_number;
                    $record->save();
                }

                // Derives paid_amount + status from the linked transactions (counts the
                // PPh23 credit toward settlement).
                $record->recalculate();

                Notification::make()
                    ->title('Payment Recorded')
                    ->success()
                    ->send();
            });
    }
}

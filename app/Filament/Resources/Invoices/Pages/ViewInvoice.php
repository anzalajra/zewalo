<?php

namespace App\Filament\Resources\Invoices\Pages;

use App\Filament\Actions\AddLateFeeAction;
use App\Filament\Actions\RecordPaymentAction;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

/**
 * Invoice detail — the primary surface for an invoice. Record Payment and Add Late
 * Fee are the two headline actions (shared factories that post the GL correctly in
 * both simple and advanced finance mode); print/edit/delete live in the ⋯ group.
 */
class ViewInvoice extends ViewRecord
{
    protected static string $resource = InvoiceResource::class;

    public function getView(): string
    {
        return 'filament.resources.invoices.pages.view-invoice';
    }

    public function getTitle(): string
    {
        return 'Invoice '.$this->getRecord()->number;
    }

    /**
     * Invoice with everything the View blade renders (line items, kits, payment
     * transactions, discount-breakdown relations).
     */
    public function getInvoiceData(): Invoice
    {
        return $this->getRecord()->loadMissing([
            'customer',
            'rentals.items.productUnit.product',
            'rentals.items.product',
            'rentals.items.productVariation',
            'rentals.items.rentalItemKits.unitKit',
            'transactions.account',
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            RecordPaymentAction::make(),
            AddLateFeeAction::make(),

            ActionGroup::make([
                Action::make('print_invoice')
                    ->label('Print / Download')
                    ->icon('heroicon-o-printer')
                    ->color('gray')
                    ->action(function (Invoice $record) {
                        foreach ($record->rentals as $rental) {
                            foreach ($rental->items as $item) {
                                $item->attachKitsFromUnit();
                            }
                        }

                        $record->load(['customer', 'rentals.items.productUnit.product', 'rentals.items.product', 'rentals.items.productVariation', 'rentals.items.rentalItemKits.unitKit']);

                        $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $record]);

                        return response()->streamDownload(
                            fn () => print ($pdf->output()),
                            'Invoice-'.$record->number.'.pdf'
                        );
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])
                ->label('Actions')
                ->icon('heroicon-o-ellipsis-horizontal')
                ->button()
                ->color('gray'),
        ];
    }
}

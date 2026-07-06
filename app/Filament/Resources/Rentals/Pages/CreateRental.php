<?php

namespace App\Filament\Resources\Rentals\Pages;

use App\Filament\Resources\Rentals\RentalResource;
use App\Models\Rental;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

/**
 * Create rental — renders the custom Livewire RentalEditor (Fase 3 port) instead
 * of the Filament form. The editor owns the whole create flow: catalog popup,
 * stock-aware assignment, ghost slots, promo picker, and totals. Tenant rental
 * quota (RentalLimitService) is enforced inside the editor's save path.
 */
class CreateRental extends Page
{
    protected static string $resource = RentalResource::class;

    public ?Rental $record = null;

    public function getView(): string
    {
        return 'filament.rentals.editor';
    }

    public function getTitle(): string|Htmlable
    {
        return '';
    }

    public function getHeading(): string|Htmlable
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}

<?php

namespace App\Filament\Pages;

use App\Models\Delivery;
use BackedEnum;
use Carbon\Carbon;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Collection;
use Livewire\Attributes\Url;
use UnitEnum;

/**
 * Jadwal Pengiriman — operational delivery board (adapted for Zewalo).
 *
 * Lists surat jalan (Delivery rows) scheduled on a chosen day, grouped by status.
 * Zewalo's Delivery model has no driver/escort concept, so this is a driverless
 * board: each card can have its scheduled time / address edited inline and its status
 * advanced. Follows the custom page + Blade + Livewire-method pattern used by Reports.
 */
class DeliverySchedule extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationLabel = 'Jadwal Pengiriman';

    protected static ?string $title = 'Jadwal Pengiriman';

    protected static string|UnitEnum|null $navigationGroup = 'Rentals';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.delivery-schedule';

    /** Selected day (Y-m-d). */
    #[Url]
    public string $date = '';

    /** out | in | all — which delivery direction to show. */
    #[Url]
    public string $direction = 'all';

    // --- Inline editor state -------------------------------------------------

    public ?int $editingId = null;

    public ?string $editScheduledAt = null;

    public ?string $editAddress = null;

    /** Per-request cache so deliveryGroups() isn't re-queried by summary(). */
    protected ?Collection $groupsCache = null;

    public static function canAccess(): bool
    {
        return auth()->user()?->hasAnyRole(['super_admin', 'admin', 'staff']) ?? false;
    }

    public function mount(): void
    {
        if (! $this->date) {
            $this->date = now()->format('Y-m-d');
        }
    }

    public function goToday(): void
    {
        $this->date = now()->format('Y-m-d');
    }

    public function shiftDay(int $days): void
    {
        $this->date = Carbon::parse($this->date)->addDays($days)->format('Y-m-d');
    }

    /**
     * Deliveries on the selected day, grouped by status.
     *
     * @return Collection<string, Collection<int, Delivery>>
     */
    public function deliveryGroups(): Collection
    {
        if ($this->groupsCache !== null) {
            return $this->groupsCache;
        }

        $query = Delivery::query()
            ->whereNotIn('status', [Delivery::STATUS_CANCELLED])
            // Match the selected day on scheduled_at, falling back to the plain `date`
            // column when scheduled_at was never set (legacy rows) so nothing drops off.
            ->where(function ($q): void {
                $q->whereDate('scheduled_at', $this->date)
                    ->orWhere(function ($q2): void {
                        $q2->whereNull('scheduled_at')->whereDate('date', $this->date);
                    });
            })
            ->with(['rental.user', 'items'])
            ->orderBy('sort_order')
            ->orderBy('scheduled_at');

        if ($this->direction !== 'all') {
            $query->where('type', $this->direction === 'out' ? Delivery::TYPE_OUT : Delivery::TYPE_IN);
        }

        $labels = Delivery::getStatusOptions();

        return $this->groupsCache = $query->get()
            ->groupBy(fn (Delivery $d): string => $labels[$d->status] ?? $d->status);
    }

    /** @return array{total:int, draft:int, pending:int, completed:int} */
    public function summary(): array
    {
        $all = $this->deliveryGroups()->flatten();

        return [
            'total' => $all->count(),
            'draft' => $all->where('status', Delivery::STATUS_DRAFT)->count(),
            'pending' => $all->where('status', Delivery::STATUS_PENDING)->count(),
            'completed' => $all->where('status', Delivery::STATUS_COMPLETED)->count(),
        ];
    }

    // --- Actions -------------------------------------------------------------

    public function openEdit(int $deliveryId): void
    {
        $delivery = Delivery::find($deliveryId);
        if (! $delivery) {
            return;
        }

        $this->editingId = $delivery->id;
        $this->editScheduledAt = $delivery->scheduled_at?->format('Y-m-d\TH:i');
        $this->editAddress = $delivery->address;
    }

    public function closeEdit(): void
    {
        $this->reset(['editingId', 'editScheduledAt', 'editAddress']);
    }

    public function saveEdit(): void
    {
        $delivery = Delivery::find($this->editingId);
        if (! $delivery) {
            $this->closeEdit();

            return;
        }

        $delivery->update([
            'scheduled_at' => $this->editScheduledAt ?: $delivery->scheduled_at,
            'address' => $this->editAddress,
        ]);

        Notification::make()
            ->title('Jadwal pengiriman disimpan')
            ->success()
            ->send();

        $this->closeEdit();
    }

    /** Advance a delivery's status inline. */
    public function setStatus(int $deliveryId, string $status): void
    {
        if (! array_key_exists($status, Delivery::getStatusOptions())) {
            return;
        }

        $delivery = Delivery::find($deliveryId);
        if (! $delivery) {
            return;
        }

        $delivery->update(['status' => $status]);

        Notification::make()
            ->title('Status pengiriman diperbarui')
            ->success()
            ->send();
    }
}

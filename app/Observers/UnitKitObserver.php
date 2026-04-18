<?php

namespace App\Observers;

use App\Models\UnitKit;
use App\Services\KitUnitLinker;

class UnitKitObserver
{
    public function __construct(protected KitUnitLinker $linker)
    {
    }

    public function saving(UnitKit $kit): void
    {
        if (! array_key_exists('track_by_serial', $kit->getAttributes())) {
            $kit->track_by_serial = true;
        }

        if (! $kit->track_by_serial) {
            $kit->linked_unit_id = null;
            $kit->serial_number = null;
            return;
        }

        $kit->linked_unit_id = $this->linker->resolveLinkedUnitId($kit);
    }
}

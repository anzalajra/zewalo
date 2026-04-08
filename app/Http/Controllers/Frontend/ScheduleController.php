<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Rental;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        return view('frontend.schedule.index');
    }

    public function events(Request $request)
    {
        $start = $request->query('start');
        $end = $request->query('end');

        $colorMap = [
            'quotation' => '#f97316',
            'confirmed' => '#3b82f6',
            'active' => '#22c55e',
            'completed' => '#a855f7',
            'cancelled' => '#6b7280',
            'late_pickup' => '#ef4444',
            'late_return' => '#ef4444',
            'partial_return' => '#eab308',
        ];

        $query = Rental::query()
            ->select(['id', 'user_id', 'rental_code', 'status', 'start_date', 'end_date'])
            ->with([
                'customer:id,name',
                'items:id,rental_id,product_unit_id',
                'items.productUnit:id,serial_number,product_id',
                'items.productUnit.product:id,name',
            ]);

        if ($start && $end) {
            $query->where(function ($q) use ($start, $end) {
                $q->where('start_date', '<', $end)
                  ->where('end_date', '>', $start);
            });
        }

        $rentals = $query->limit(500)->get();

        $events = $rentals->map(function (Rental $rental) use ($colorMap) {
            $color = $colorMap[$rental->status] ?? '#6b7280';

            $items = $rental->items->map(function ($item) {
                $pu = $item->productUnit;
                return ($pu?->product?->name ?? '-') . ' (' . ($pu->serial_number ?? '-') . ')';
            })->join(', ');

            return [
                'id' => $rental->id,
                'title' => ($rental->customer->name ?? 'Unknown') . ' — ' . ($rental->rental_code ?? ''),
                'start' => $rental->start_date?->toIso8601String(),
                'end' => $rental->end_date?->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'status' => ucfirst(str_replace('_', ' ', $rental->status)),
                    'items' => $items,
                    'start_formatted' => $rental->start_date?->format('d M Y H:i'),
                    'end_formatted' => $rental->end_date?->format('d M Y H:i'),
                ],
            ];
        });

        return response()->json($events->values());
    }
}

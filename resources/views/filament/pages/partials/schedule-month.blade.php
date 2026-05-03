<div class="zw-month">
    <div class="zw-month__head">
        @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d)
            <div>{{ $d }}</div>
        @endforeach
    </div>

    @foreach ($weeks as $week)
        <div class="zw-month__week">
            @foreach ($week as $cell)
                @php
                    $allItems = collect($cell['all'])->map(function ($r) use ($sc) {
                        $c = $sc[$r['status']] ?? $sc['cancelled'];
                        return [
                            'id'       => $r['id'],
                            'customer' => $r['customer'],
                            'time'     => $r['start']->format('H:i'),
                            'color'    => $c['solid'],
                            'bg'       => $c['bg'],
                            'fg'       => $c['fg'],
                            'label'    => $c['label'],
                        ];
                    })->values()->toJson(JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
                    $cellTitle = \Carbon\Carbon::parse($cell['date'])->translatedFormat('l, d F Y');
                    $cellTitleJson = json_encode($cellTitle, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
                @endphp
                <div class="zw-month__cell {{ $cell['inMonth'] ? '' : 'zw-month__cell--out' }}">
                    <span class="zw-month__day {{ $cell['isToday'] ? 'zw-month__day--today' : '' }} {{ $cell['inMonth'] ? '' : 'zw-month__day--out' }}">
                        {{ $cell['day'] }}
                    </span>
                    @foreach ($cell['visible'] as $r)
                        @php $c = $sc[$r['status']] ?? $sc['cancelled']; @endphp
                        <button type="button" wire:click="mountAction('viewRentalDetails', { rentalId: {{ $r['id'] }} })"
                                class="zw-month__bar" style="background:{{ $c['solid'] }}"
                                title="{{ $r['customer'] }} — {{ $c['label'] }}">
                            {{ $r['customer'] }}
                        </button>
                    @endforeach
                    @if ($cell['overflow'] > 0)
                        <button type="button" class="zw-month__more"
                                onclick='window.dispatchEvent(new CustomEvent("zw-open-overflow", { detail: { title: {{ $cellTitleJson }}, items: {{ $allItems }} } }))'>
                            +{{ $cell['overflow'] }} more
                        </button>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach
</div>

<div class="zw-month">
    <div class="zw-month__head">
        @foreach (['Sen','Sel','Rab','Kam','Jum','Sab','Min'] as $d)
            <div>{{ $d }}</div>
        @endforeach
    </div>

    @foreach ($weeks as $week)
        @php
            // Bar row count — how many lanes are needed (capped by max visible lanes)
            $maxLane = -1;
            foreach ($week['bars'] as $b) {
                if ($b['lane'] > $maxLane) $maxLane = $b['lane'];
            }
            $laneCount = $maxLane + 1; // 0 if no bars
        @endphp
        <div class="zw-month__week">
            {{-- Day-number row --}}
            <div class="zw-month__daynums">
                @foreach ($week['days'] as $cell)
                    <div class="zw-month__cell {{ $cell['inMonth'] ? '' : 'zw-month__cell--out' }}">
                        <span class="zw-month__day {{ $cell['isToday'] ? 'zw-month__day--today' : '' }} {{ $cell['inMonth'] ? '' : 'zw-month__day--out' }}">
                            {{ $cell['day'] }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Lane rows: each rental is ONE bar spanning multiple columns --}}
            @if ($laneCount > 0)
                <div class="zw-month__lanes" style="grid-template-rows: repeat({{ $laneCount }}, 22px);">
                    @foreach ($week['bars'] as $b)
                        @php $c = $sc[$b['rental']['status']] ?? $sc['cancelled']; @endphp
                        <button type="button"
                                wire:click="mountAction('viewRentalDetails', { rentalId: {{ $b['rental']['id'] }} })"
                                class="zw-month__bar"
                                style="grid-column: {{ $b['startCol'] + 1 }} / span {{ $b['span'] }}; grid-row: {{ $b['lane'] + 1 }}; background: {{ $c['solid'] }};"
                                title="{{ $b['rental']['customer'] }} — {{ $c['label'] }} ({{ $b['rental']['start']->format('d M') }} → {{ $b['rental']['end']->format('d M') }})">
                            <span class="zw-month__bar__time">{{ $b['rental']['start']->format('H:i') }}</span>
                            <span class="zw-month__bar__name">{{ $b['rental']['customer'] }}</span>
                        </button>
                    @endforeach
                </div>
            @endif

            {{-- Overflow row: per-column "+N more" badges --}}
            @php
                $hasOverflow = false;
                foreach ($week['overflowByCol'] as $n) { if ($n > 0) { $hasOverflow = true; break; } }
            @endphp
            @if ($hasOverflow)
                <div class="zw-month__overflow-row">
                    @foreach ($week['days'] as $col => $cell)
                        <div class="zw-month__overflow-cell">
                            @if (($week['overflowByCol'][$col] ?? 0) > 0)
                                @php
                                    $allItems = collect($week['allByCol'][$col] ?? [])->map(function ($r) use ($sc) {
                                        $cc = $sc[$r['status']] ?? $sc['cancelled'];
                                        return [
                                            'id'       => $r['id'],
                                            'customer' => $r['customer'],
                                            'time'     => $r['start']->format('H:i'),
                                            'color'    => $cc['solid'],
                                            'bg'       => $cc['bg'],
                                            'fg'       => $cc['fg'],
                                            'label'    => $cc['label'],
                                        ];
                                    })->values()->toJson(JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
                                    $cellTitle = \Carbon\Carbon::parse($cell['date'])->translatedFormat('l, d F Y');
                                    $cellTitleJson = json_encode($cellTitle, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP);
                                @endphp
                                <button type="button" class="zw-month__more"
                                        onclick='window.dispatchEvent(new CustomEvent("zw-open-overflow", { detail: { title: {{ $cellTitleJson }}, items: {{ $allItems }} } }))'>
                                    +{{ $week['overflowByCol'][$col] }} more
                                </button>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endforeach
</div>

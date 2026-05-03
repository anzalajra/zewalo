<div class="zw-week">
    <div class="zw-week__head">
        @foreach ($grid['days'] as $d)
            <div>
                <div class="zw-week__dayname">{{ $d['short'] }}</div>
                @if ($d['isToday'])
                    <div class="zw-week__daynum zw-week__daynum--today">{{ $d['day'] }}</div>
                @else
                    <div class="zw-week__daynum">{{ $d['day'] }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="zw-week__body">
        @forelse ($grid['bars'] as $b)
            @php $c = $sc[$b['rental']['status']] ?? $sc['cancelled']; @endphp
            <div class="zw-week__bar">
                <button type="button" wire:click="mountAction('viewRentalDetails', { rentalId: {{ $b['rental']['id'] }} })"
                        class="zw-week__rental"
                        style="--start: {{ $b['startCol'] + 1 }}; --span: {{ $b['span'] }}; --bg: {{ $c['solid'] }}; background: {{ $c['solid'] }}"
                        title="{{ $b['rental']['customer'] }} — {{ $c['label'] }}">
                    <div>{{ $b['rental']['customer'] }}</div>
                    <div class="zw-week__rental__sub">
                        {{ $b['rental']['start']->format('d M') }} → {{ $b['rental']['end']->format('d M') }}
                    </div>
                </button>
            </div>
        @empty
            <div style="padding:30px;text-align:center;color:#9ca3af;font-size:13px;">Tidak ada rental di minggu ini.</div>
        @endforelse
    </div>
</div>

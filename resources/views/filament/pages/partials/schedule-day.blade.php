<div class="zw-day">
    <div class="zw-day__grid">
        <div class="zw-day__hours">
            @for ($h = 0; $h < 24; $h++)
                <div class="zw-day__hour">{{ str_pad($h, 2, '0', STR_PAD_LEFT) }}:00</div>
            @endfor
        </div>
        <div class="zw-day__events">
            @foreach ($layout['events'] as $e)
                @php
                    $c = $sc[$e['rental']['status']] ?? $sc['cancelled'];
                    $top    = ($e['startMin'] / 60) * 48;
                    $height = max(28, (($e['endMin'] - $e['startMin']) / 60) * 48 - 4);
                    $width  = 100 / $layout['totalLanes'];
                    $left   = $width * $e['lane'];
                @endphp
                <div class="zw-day__event"
                     style="top:{{ $top }}px; height:{{ $height }}px; left:calc({{ $left }}% + 4px); width:calc({{ $width }}% - 8px); background:{{ $c['solid'] }};"
                     wire:click="mountAction('viewRentalDetails', { rentalId: {{ $e['rental']['id'] }} })">
                    <div>{{ $e['rental']['customer'] }}</div>
                    <div class="zw-day__event__time">
                        {{ $e['rental']['start']->format('H:i') }} – {{ $e['rental']['end']->format('H:i') }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

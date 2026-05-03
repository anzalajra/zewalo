<div class="zw-prod">
    <div class="zw-prod__search">
        <x-filament::input.wrapper prefix-icon="heroicon-m-magnifying-glass">
            <x-filament::input
                wire:model.live.debounce.500ms="search"
                type="search"
                placeholder="Cari produk atau unit…"
            />
        </x-filament::input.wrapper>
    </div>

    <div class="zw-prod__shell">
        <div class="zw-prod__scroll">
            <table class="zw-prod__table">
                <thead>
                    <tr>
                        <th class="zw-prod__th-spacer" rowspan="2">Product / Unit</th>
                        @foreach ($header['months'] as $m)
                            <th class="zw-prod__month" colspan="{{ $m['count'] }}">{{ $m['label'] }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($header['days'] as $d)
                            <th class="zw-prod__day {{ $d['isToday'] ? 'zw-prod__day--today' : '' }}">
                                <div class="zw-prod__day__short">{{ $d['short'] }}</div>
                                <div class="zw-prod__day__num">{{ $d['day'] }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $group)
                        <tr wire:key="product-row-{{ $group['product']->id }}">
                            <td class="zw-prod__product" colspan="{{ count($header['days']) + 1 }}">
                                {{ $group['product']->name }}
                            </td>
                        </tr>
                        @foreach ($group['units'] as $row)
                            <tr wire:key="unit-row-{{ $row['unit']->id }}">
                                <td class="zw-prod__sku">
                                    <span class="zw-prod__sku-tag">{{ $row['unit']->serial_number }}</span>
                                </td>
                                @foreach ($row['cells'] as $cell)
                                    <td class="zw-prod__cell {{ $cell['isToday'] ? 'zw-prod__day--today' : '' }}">
                                        @foreach ($cell['segments'] as $seg)
                                            @php $c = $sc[$seg['rental']['status']] ?? $sc['cancelled']; @endphp
                                            <button type="button"
                                                wire:click="mountAction('viewRentalDetails', { rentalId: {{ $seg['rental']['id'] }} })"
                                                class="zw-prod__seg"
                                                style="left:{{ $seg['left'] }}%; width:{{ max(8, $seg['width']) }}%; background:{{ $c['solid'] }}; border-top-left-radius:{{ $seg['continuesLeft'] ? '0' : '4px' }}; border-bottom-left-radius:{{ $seg['continuesLeft'] ? '0' : '4px' }}; border-top-right-radius:{{ $seg['continuesRight'] ? '0' : '4px' }}; border-bottom-right-radius:{{ $seg['continuesRight'] ? '0' : '4px' }};"
                                                title="{{ $seg['rental']['customer'] }} — {{ $seg['rental']['start']->format('d M H:i') }} → {{ $seg['rental']['end']->format('d M H:i') }}">
                                                <span class="zw-prod__seg__name">{{ $seg['rental']['customer'] }}</span>
                                            </button>
                                        @endforeach
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="{{ count($header['days']) + 1 }}" style="padding:40px;text-align:center;color:#9ca3af;font-size:13px;">
                                Tidak ada produk ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="padding:14px;border-top:1px solid #f3f4f6;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:10px;">
            <div style="width:140px;">
                <x-filament::input.wrapper prefix="Per page">
                    <x-filament::input.select wire:model.live="perPage">
                        <option value="15">15</option>
                        <option value="35">35</option>
                        <option value="55">55</option>
                        <option value="75">75</option>
                    </x-filament::input.select>
                </x-filament::input.wrapper>
            </div>
            <div>{{ $products->links() }}</div>
        </div>
    </div>
</div>

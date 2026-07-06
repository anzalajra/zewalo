<x-filament-panels::page>
    @php
        $tb = $this->trialBalance;
        $is = $this->incomeStatement;
        $bs = $this->balanceSheet;
        $ar = $this->arAging;
        $ap = $this->apAging;
        $gl = $this->generalLedger;
        $taxRecap = $this->taxRecap;
        $pph23Recap = $this->pph23Recap;
        $buckets = \App\Services\AgingReportService::BUCKETS;
        $bucketLabels = $this->bucketLabels();
        $rp = fn ($n) => 'Rp ' . number_format((float) $n, 0, ',', '.');
    @endphp

    {{-- Date range filter --}}
    <x-filament::section>
        <div class="flex flex-wrap items-end gap-4">
            <div>
                <label class="text-xs font-medium text-gray-500">Dari Tanggal</label>
                <input type="date" wire:model.live="startDate"
                       class="block mt-1 rounded-lg border-gray-300 dark:bg-gray-800 text-sm"/>
            </div>
            <div>
                <label class="text-xs font-medium text-gray-500">Sampai Tanggal</label>
                <input type="date" wire:model.live="endDate"
                       class="block mt-1 rounded-lg border-gray-300 dark:bg-gray-800 text-sm"/>
            </div>
        </div>
    </x-filament::section>

    {{-- Trial Balance --}}
    <x-filament::section>
        <x-slot name="heading">Neraca Saldo (Trial Balance)</x-slot>
        <x-slot name="description">Per {{ $this->endDate }}</x-slot>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-xs uppercase text-gray-500">
                        <th class="py-2 pr-4">Kode</th>
                        <th class="py-2 pr-4">Akun</th>
                        <th class="py-2 pr-4 text-right">Debit</th>
                        <th class="py-2 text-right">Kredit</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tb['rows'] as $r)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-1.5 pr-4 font-mono text-xs">{{ $r['code'] }}</td>
                            <td class="py-1.5 pr-4">{{ $r['name'] }}</td>
                            <td class="py-1.5 pr-4 text-right">{{ $r['debit'] > 0 ? $rp($r['debit']) : '—' }}</td>
                            <td class="py-1.5 text-right">{{ $r['credit'] > 0 ? $rp($r['credit']) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="py-4 text-center text-gray-400">Belum ada jurnal.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 font-semibold">
                        <td class="py-2 pr-4" colspan="2">Total</td>
                        <td class="py-2 pr-4 text-right">{{ $rp($tb['total_debit']) }}</td>
                        <td class="py-2 text-right">{{ $rp($tb['total_credit']) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="mt-2">
            <x-filament::badge :color="$tb['balanced'] ? 'success' : 'danger'">
                {{ $tb['balanced'] ? 'Seimbang' : 'TIDAK SEIMBANG — selisih ' . $rp($tb['total_debit'] - $tb['total_credit']) }}
            </x-filament::badge>
        </div>
    </x-filament::section>

    {{-- Income Statement --}}
    <x-filament::section>
        <x-slot name="heading">Laba Rugi (Income Statement)</x-slot>
        <x-slot name="description">{{ $this->startDate }} – {{ $this->endDate }}</x-slot>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <div class="mb-1 text-xs font-semibold uppercase text-gray-500">Pendapatan</div>
                <table class="w-full text-sm">
                    @foreach ($is['revenue'] as $r)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-1">{{ $r['name'] }}</td>
                            <td class="py-1 text-right">{{ $rp($r['amount']) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-semibold"><td class="py-1.5">Total Pendapatan</td><td class="py-1.5 text-right">{{ $rp($is['total_revenue']) }}</td></tr>
                </table>
            </div>
            <div>
                <div class="mb-1 text-xs font-semibold uppercase text-gray-500">Beban</div>
                <table class="w-full text-sm">
                    @foreach ($is['expense'] as $r)
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-1">{{ $r['name'] }}</td>
                            <td class="py-1 text-right">{{ $rp($r['amount']) }}</td>
                        </tr>
                    @endforeach
                    <tr class="font-semibold"><td class="py-1.5">Total Beban</td><td class="py-1.5 text-right">{{ $rp($is['total_expense']) }}</td></tr>
                </table>
            </div>
        </div>
        <div class="mt-3 text-right text-base font-bold">
            Laba Bersih: <span class="{{ $is['net_income'] >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $rp($is['net_income']) }}</span>
        </div>
    </x-filament::section>

    {{-- Balance Sheet --}}
    <x-filament::section>
        <x-slot name="heading">Neraca (Balance Sheet)</x-slot>
        <x-slot name="description">Per {{ $this->endDate }}</x-slot>
        <div class="grid gap-6 md:grid-cols-2">
            <div>
                <div class="mb-1 text-xs font-semibold uppercase text-gray-500">Aset</div>
                <table class="w-full text-sm">
                    @foreach ($bs['assets'] as $r)
                        <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-1">{{ $r['name'] }}</td><td class="py-1 text-right">{{ $rp($r['amount']) }}</td></tr>
                    @endforeach
                    <tr class="font-semibold"><td class="py-1.5">Total Aset</td><td class="py-1.5 text-right">{{ $rp($bs['total_assets']) }}</td></tr>
                </table>
            </div>
            <div>
                <div class="mb-1 text-xs font-semibold uppercase text-gray-500">Kewajiban & Ekuitas</div>
                <table class="w-full text-sm">
                    @foreach ($bs['liabilities'] as $r)
                        <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-1">{{ $r['name'] }}</td><td class="py-1 text-right">{{ $rp($r['amount']) }}</td></tr>
                    @endforeach
                    @foreach ($bs['equity'] as $r)
                        <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-1">{{ $r['name'] }}</td><td class="py-1 text-right">{{ $rp($r['amount']) }}</td></tr>
                    @endforeach
                    <tr class="border-b border-gray-100 dark:border-gray-700"><td class="py-1">Laba Berjalan</td><td class="py-1 text-right">{{ $rp($bs['net_income']) }}</td></tr>
                    <tr class="font-semibold"><td class="py-1.5">Total Kewajiban + Ekuitas</td><td class="py-1.5 text-right">{{ $rp($bs['total_liabilities'] + $bs['total_equity']) }}</td></tr>
                </table>
            </div>
        </div>
        <div class="mt-2">
            <x-filament::badge :color="$bs['balanced'] ? 'success' : 'danger'">
                {{ $bs['balanced'] ? 'Seimbang (Aset = Kewajiban + Ekuitas)' : 'TIDAK SEIMBANG — selisih ' . $rp($bs['difference']) }}
            </x-filament::badge>
        </div>
    </x-filament::section>

    {{-- AR / AP Aging --}}
    <div class="grid gap-6 lg:grid-cols-2">
        @foreach (['Umur Piutang (AR)' => $ar, 'Umur Hutang (AP)' => $ap] as $title => $aging)
            <x-filament::section>
                <x-slot name="heading">{{ $title }}</x-slot>
                <div class="overflow-x-auto">
                    <table class="w-full text-xs">
                        <thead>
                            <tr class="border-b text-left uppercase text-gray-500">
                                <th class="py-1.5 pr-2">No</th>
                                <th class="py-1.5 pr-2">Pihak</th>
                                @foreach ($buckets as $b)
                                    <th class="py-1.5 pr-2 text-right">{{ $bucketLabels[$b] }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($aging['rows'] as $row)
                                <tr class="border-b border-gray-100 dark:border-gray-700">
                                    <td class="py-1 pr-2 font-mono">{{ $row['number'] }}</td>
                                    <td class="py-1 pr-2">{{ $row['party'] }}</td>
                                    @foreach ($buckets as $b)
                                        <td class="py-1 pr-2 text-right">{{ $row['bucket'] === $b ? $rp($row['balance']) : '—' }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr><td colspan="{{ count($buckets) + 2 }}" class="py-3 text-center text-gray-400">Tidak ada.</td></tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr class="border-t-2 font-semibold">
                                <td class="py-1.5 pr-2" colspan="2">Total ({{ $rp($aging['grand_total']) }})</td>
                                @foreach ($buckets as $b)
                                    <td class="py-1.5 pr-2 text-right">{{ $rp($aging['totals'][$b]) }}</td>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </x-filament::section>
        @endforeach
    </div>

    {{-- General Ledger drill-down --}}
    <x-filament::section>
        <x-slot name="heading">Buku Besar (General Ledger)</x-slot>
        <div class="mb-3">
            <label class="text-xs font-medium text-gray-500">Pilih Akun</label>
            <select wire:model.live="ledgerAccountId" class="block mt-1 rounded-lg border-gray-300 dark:bg-gray-800 text-sm">
                <option value="">— pilih akun —</option>
                @foreach ($this->accountOptions() as $id => $label)
                    <option value="{{ $id }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        @if ($gl && $gl['account'])
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-xs uppercase text-gray-500">
                            <th class="py-2 pr-3">Tanggal</th>
                            <th class="py-2 pr-3">Ref</th>
                            <th class="py-2 pr-3">Keterangan</th>
                            <th class="py-2 pr-3 text-right">Debit</th>
                            <th class="py-2 pr-3 text-right">Kredit</th>
                            <th class="py-2 text-right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100 dark:border-gray-700 text-gray-500">
                            <td class="py-1.5 pr-3" colspan="5">Saldo Awal</td>
                            <td class="py-1.5 text-right font-medium">{{ $rp($gl['opening']) }}</td>
                        </tr>
                        @foreach ($gl['rows'] as $row)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1.5 pr-3">{{ \Illuminate\Support\Carbon::parse($row['date'])->format('d/m/Y') }}</td>
                                <td class="py-1.5 pr-3 font-mono text-xs">{{ $row['ref'] }}</td>
                                <td class="py-1.5 pr-3">{{ $row['description'] }}</td>
                                <td class="py-1.5 pr-3 text-right">{{ $row['debit'] > 0 ? $rp($row['debit']) : '—' }}</td>
                                <td class="py-1.5 pr-3 text-right">{{ $row['credit'] > 0 ? $rp($row['credit']) : '—' }}</td>
                                <td class="py-1.5 text-right font-medium">{{ $rp($row['balance']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 font-semibold">
                            <td class="py-2 pr-3" colspan="3">Saldo Akhir</td>
                            <td class="py-2 pr-3 text-right">{{ $rp($gl['total_debit']) }}</td>
                            <td class="py-2 pr-3 text-right">{{ $rp($gl['total_credit']) }}</td>
                            <td class="py-2 text-right">{{ $rp($gl['closing']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </x-filament::section>

    {{-- PPN Keluaran (Output Tax) recap --}}
    <x-filament::section collapsible collapsed>
        <x-slot name="heading">Rekap PPN Keluaran</x-slot>
        <x-slot name="headerEnd">
            <x-filament::button size="xs" color="gray" icon="heroicon-o-arrow-down-tray"
                wire:click="exportTaxCsv">CSV</x-filament::button>
        </x-slot>
        @if (empty($taxRecap['lines']))
            <p class="text-sm text-gray-500">Tidak ada faktur kena pajak di periode ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-gray-500">
                            <th class="py-1.5 pr-3">Tanggal</th>
                            <th class="py-1.5 pr-3">No Invoice</th>
                            <th class="py-1.5 pr-3">No Faktur Pajak</th>
                            <th class="py-1.5 pr-3">Pelanggan</th>
                            <th class="py-1.5 pr-3 text-right">DPP</th>
                            <th class="py-1.5 text-right">PPN</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($taxRecap['lines'] as $line)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1.5 pr-3">{{ $line['date'] }}</td>
                                <td class="py-1.5 pr-3 font-mono text-xs">{{ $line['invoice_number'] }}</td>
                                <td class="py-1.5 pr-3 font-mono text-xs">{{ $line['tax_invoice_number'] ?: '—' }}</td>
                                <td class="py-1.5 pr-3">{{ $line['customer'] }}</td>
                                <td class="py-1.5 pr-3 text-right">{{ $rp($line['dpp']) }}</td>
                                <td class="py-1.5 text-right">{{ $rp($line['ppn']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 font-semibold">
                            <td class="py-2 pr-3" colspan="4">TOTAL</td>
                            <td class="py-2 pr-3 text-right">{{ $rp($taxRecap['total_dpp']) }}</td>
                            <td class="py-2 text-right">{{ $rp($taxRecap['total_ppn']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </x-filament::section>

    {{-- PPh 23 withholding (prepaid-tax credit) recap --}}
    <x-filament::section collapsible collapsed>
        <x-slot name="heading">Rekap PPh 23 (Kredit Pajak)</x-slot>
        <x-slot name="headerEnd">
            <x-filament::button size="xs" color="gray" icon="heroicon-o-arrow-down-tray"
                wire:click="exportWithholdingCsv">CSV</x-filament::button>
        </x-slot>
        @if (empty($pph23Recap['lines']))
            <p class="text-sm text-gray-500">Tidak ada PPh 23 yang dipotong pelanggan di periode ini.</p>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b text-left text-gray-500">
                            <th class="py-1.5 pr-3">Tanggal</th>
                            <th class="py-1.5 pr-3">No Invoice</th>
                            <th class="py-1.5 pr-3">No Bukti Potong</th>
                            <th class="py-1.5 pr-3">Pelanggan</th>
                            <th class="py-1.5 pr-3 text-right">DPP</th>
                            <th class="py-1.5 text-right">PPh 23</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pph23Recap['lines'] as $line)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="py-1.5 pr-3">{{ $line['date'] }}</td>
                                <td class="py-1.5 pr-3 font-mono text-xs">{{ $line['invoice_number'] }}</td>
                                <td class="py-1.5 pr-3 font-mono text-xs">{{ $line['bukti_potong'] ?: '—' }}</td>
                                <td class="py-1.5 pr-3">{{ $line['customer'] }}</td>
                                <td class="py-1.5 pr-3 text-right">{{ $rp($line['dpp']) }}</td>
                                <td class="py-1.5 text-right">{{ $rp($line['pph23']) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 font-semibold">
                            <td class="py-2 pr-3" colspan="4">TOTAL</td>
                            <td class="py-2 pr-3 text-right">{{ $rp($pph23Recap['total_dpp']) }}</td>
                            <td class="py-2 text-right">{{ $rp($pph23Recap['total_pph23']) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @endif
    </x-filament::section>
</x-filament-panels::page>

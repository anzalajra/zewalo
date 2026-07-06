{{--
    Delivery handover capture: recipient name + drawn signature + per-item photos.
    Requires the host page to use App\Filament\Concerns\CapturesDeliveryHandover
    (properties: showHandover, handoverRecipient, handoverSignature, handoverPhoto).
--}}
<div>
    <div class="mb-3">
        <x-filament::button color="gray" icon="heroicon-o-pencil-square" wire:click="openHandover">
            Tanda Terima (Tanda Tangan)
        </x-filament::button>
    </div>

    @if ($showHandover)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4" wire:click.self="closeHandover">
            <div class="w-full max-w-lg rounded-xl bg-white p-5 shadow-xl dark:bg-gray-900"
                 x-data="signaturePad()" x-init="init()">
                <h3 class="text-base font-semibold text-gray-900 dark:text-white">Tanda Terima Serah Terima</h3>

                <div class="mt-4 space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-500">Nama Penerima</label>
                        <input type="text" wire:model="handoverRecipient"
                            class="mt-1 w-full rounded-lg border-gray-300 text-sm dark:bg-gray-800 dark:border-gray-700" />
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-500">Tanda Tangan</label>
                        <div class="mt-1 rounded-lg border border-gray-300 dark:border-gray-700">
                            <canvas x-ref="pad" class="w-full touch-none" height="160"
                                style="width:100%; height:160px; border-radius:0.5rem; background:#fff;"></canvas>
                        </div>
                        <div class="mt-2 flex gap-2">
                            <x-filament::button size="xs" color="gray" type="button" x-on:click="clearPad()">Hapus</x-filament::button>
                            @if ($handoverSignature)
                                <span class="text-xs text-success-600 self-center">Tanda tangan tersimpan sebelumnya</span>
                            @endif
                        </div>
                    </div>

                    {{-- Per-item handover photos --}}
                    @if ($delivery)
                        <div>
                            <label class="block text-xs font-medium text-gray-500">Foto Kondisi (per item)</label>
                            <div class="mt-1 space-y-2 max-h-40 overflow-y-auto">
                                @foreach ($delivery->items as $it)
                                    <div class="flex items-center justify-between gap-2 rounded border border-gray-100 px-2 py-1 text-xs dark:border-gray-700">
                                        <span class="truncate">{{ $this->itemLabel($it) }}</span>
                                        <span class="flex items-center gap-2">
                                            <span class="text-gray-400">{{ is_array($it->photos) ? count($it->photos) : 0 }} foto</span>
                                            <input type="file" accept="image/*" wire:model="handoverPhoto"
                                                class="text-[10px] max-w-[130px]" />
                                            <x-filament::button size="xs" color="primary" type="button"
                                                wire:click="addItemPhoto({{ $it->id }})"
                                                wire:loading.attr="disabled">+</x-filament::button>
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="mt-5 flex justify-end gap-2">
                    <x-filament::button color="gray" type="button" wire:click="closeHandover">Batal</x-filament::button>
                    <x-filament::button color="primary" type="button" x-on:click="commit()">Simpan</x-filament::button>
                </div>
            </div>
        </div>

        <script>
            function signaturePad() {
                return {
                    ctx: null, drawing: false, dirty: false,
                    init() {
                        const c = this.$refs.pad;
                        // Scale canvas backing store to element size for crisp lines.
                        const rect = c.getBoundingClientRect();
                        c.width = rect.width; c.height = 160;
                        this.ctx = c.getContext('2d');
                        this.ctx.lineWidth = 2; this.ctx.lineCap = 'round'; this.ctx.strokeStyle = '#111827';
                        const pos = (e) => {
                            const r = c.getBoundingClientRect();
                            const p = e.touches ? e.touches[0] : e;
                            return { x: p.clientX - r.left, y: p.clientY - r.top };
                        };
                        const start = (e) => { this.drawing = true; const {x,y} = pos(e); this.ctx.beginPath(); this.ctx.moveTo(x,y); e.preventDefault(); };
                        const move = (e) => { if (!this.drawing) return; const {x,y} = pos(e); this.ctx.lineTo(x,y); this.ctx.stroke(); this.dirty = true; e.preventDefault(); };
                        const end = () => { this.drawing = false; };
                        c.addEventListener('mousedown', start); c.addEventListener('mousemove', move); window.addEventListener('mouseup', end);
                        c.addEventListener('touchstart', start); c.addEventListener('touchmove', move); c.addEventListener('touchend', end);
                    },
                    clearPad() { this.ctx.clearRect(0,0,this.$refs.pad.width,this.$refs.pad.height); this.dirty = false; },
                    commit() {
                        if (this.dirty) {
                            @this.set('handoverSignature', this.$refs.pad.toDataURL('image/png'));
                        }
                        @this.call('saveHandover');
                    },
                };
            }
        </script>
    @endif
</div>

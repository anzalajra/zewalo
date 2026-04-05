<x-filament-panels::page>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        #holiday-calendar .flatpickr-calendar {
            box-shadow: none;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.5rem;
            width: 100%;
        }
        #holiday-calendar .flatpickr-innerContainer {
            width: 100%;
        }
        #holiday-calendar .flatpickr-rContainer {
            width: 100%;
        }
        #holiday-calendar .flatpickr-days {
            width: 100% !important;
        }
        #holiday-calendar .dayContainer {
            width: 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
        }
        .dark #holiday-calendar .flatpickr-calendar {
            background: rgb(17 24 39);
            border-color: rgb(55 65 81);
            color: rgb(209 213 219);
        }
        .dark #holiday-calendar .flatpickr-day {
            color: rgb(209 213 219);
        }
        .dark #holiday-calendar .flatpickr-day:hover {
            background: rgb(55 65 81);
        }
        .dark #holiday-calendar .flatpickr-months .flatpickr-month,
        .dark #holiday-calendar .flatpickr-weekdays {
            background: rgb(17 24 39);
            color: rgb(209 213 219);
        }
        .dark #holiday-calendar .flatpickr-current-month,
        .dark #holiday-calendar span.cur-month,
        .dark #holiday-calendar .numInputWrapper input {
            color: rgb(209 213 219);
        }
        .dark #holiday-calendar .flatpickr-prev-month svg,
        .dark #holiday-calendar .flatpickr-next-month svg {
            fill: rgb(209 213 219);
        }
        .dark #holiday-calendar .flatpickr-day.flatpickr-disabled {
            color: rgb(107 114 128);
        }
        #holiday-calendar .flatpickr-day.inRange {
            background: rgb(239 68 68 / 0.15);
            border-color: transparent;
            box-shadow: -5px 0 0 rgb(239 68 68 / 0.15), 5px 0 0 rgb(239 68 68 / 0.15);
        }
        #holiday-calendar .flatpickr-day.selected,
        #holiday-calendar .flatpickr-day.startRange,
        #holiday-calendar .flatpickr-day.endRange {
            background: rgb(239 68 68);
            border-color: rgb(239 68 68);
        }
        #holiday-calendar .flatpickr-day.selected:hover,
        #holiday-calendar .flatpickr-day.startRange:hover,
        #holiday-calendar .flatpickr-day.endRange:hover {
            background: rgb(220 38 38);
            border-color: rgb(220 38 38);
        }
    </style>

    <form wire:submit="save">
        {{ $this->form }}

        {{-- Operational Schedule Section --}}
        <div
            wire:ignore
            class="mt-6 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
            x-data="scheduleManager(@js($this->operationalSchedule))"
        >
            <div class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                <div class="grid flex-1 gap-y-1">
                    <h3 class="fi-section-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Operational Schedule
                    </h3>
                    <p class="fi-section-description text-sm text-gray-500 dark:text-gray-400">
                        Atur hari dan jam operasional. Perubahan tersimpan otomatis.
                    </p>
                </div>
            </div>

            <div class="fi-section-content border-t border-gray-100 dark:border-white/10 px-6 py-4">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-white/10">
                                <th class="pb-3 text-left font-medium text-gray-500 dark:text-gray-400 w-28">Hari</th>
                                <th class="pb-3 text-center font-medium text-gray-500 dark:text-gray-400 w-20">Buka</th>
                                <th class="pb-3 text-left font-medium text-gray-500 dark:text-gray-400">Jam Buka</th>
                                <th class="pb-3 text-left font-medium text-gray-500 dark:text-gray-400">Jam Tutup</th>
                                <th class="pb-3 text-center font-medium text-gray-500 dark:text-gray-400 w-24">24 Jam</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-white/5">
                            <template x-for="day in dayOrder" :key="day">
                                <tr :class="schedule[day].enabled ? '' : 'opacity-50'">
                                    {{-- Day name --}}
                                    <td class="py-3 pr-4">
                                        <span class="font-medium text-gray-900 dark:text-white" x-text="dayNames[day]"></span>
                                    </td>

                                    {{-- Enabled toggle --}}
                                    <td class="py-3 text-center">
                                        <button
                                            type="button"
                                            @click="toggleDay(day)"
                                            :class="schedule[day].enabled
                                                ? 'bg-primary-600 dark:bg-primary-500'
                                                : 'bg-gray-200 dark:bg-white/10'"
                                            class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                                        >
                                            <span
                                                :class="schedule[day].enabled ? 'translate-x-5' : 'translate-x-0'"
                                                class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                                            ></span>
                                        </button>
                                    </td>

                                    {{-- Open time --}}
                                    <td class="py-3 pr-3">
                                        <input
                                            type="time"
                                            x-model="schedule[day].open"
                                            @change="sync()"
                                            :disabled="!schedule[day].enabled || schedule[day].is_24h"
                                            class="fi-input block w-full rounded-lg border-0 bg-white py-1.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/20 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                    </td>

                                    {{-- Close time --}}
                                    <td class="py-3 pr-3">
                                        <template x-if="schedule[day].is_24h">
                                            <span class="inline-flex items-center gap-1 rounded-md bg-green-50 dark:bg-green-950/30 px-2.5 py-1.5 text-xs font-medium text-green-700 dark:text-green-400 ring-1 ring-inset ring-green-600/20">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                                </svg>
                                                Buka 24 Jam
                                            </span>
                                        </template>
                                        <template x-if="!schedule[day].is_24h">
                                            <input
                                                type="time"
                                                x-model="schedule[day].close"
                                                @change="sync()"
                                                :disabled="!schedule[day].enabled"
                                                class="fi-input block w-full rounded-lg border-0 bg-white py-1.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/20 disabled:cursor-not-allowed disabled:opacity-50"
                                            >
                                        </template>
                                    </td>

                                    {{-- 24h toggle --}}
                                    <td class="py-3 text-center">
                                        <button
                                            type="button"
                                            @click="toggle24h(day)"
                                            :disabled="!schedule[day].enabled"
                                            :class="schedule[day].is_24h
                                                ? 'bg-green-600 text-white ring-green-600 dark:bg-green-500'
                                                : 'bg-white text-gray-700 ring-gray-300 dark:bg-white/5 dark:text-gray-300 dark:ring-white/20'"
                                            class="inline-flex items-center rounded-md px-2.5 py-1.5 text-xs font-semibold shadow-sm ring-1 ring-inset transition disabled:cursor-not-allowed disabled:opacity-40"
                                        >
                                            24 Jam
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Custom Holiday Section --}}
        <div
            wire:ignore
            class="mt-6 fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10"
            x-data="holidayManager(@js($this->holidays))"
            x-init="initCalendar()"
        >
            <div class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                <div class="grid flex-1 gap-y-1">
                    <h3 class="fi-section-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        Holidays
                    </h3>
                    <p class="fi-section-description text-sm text-gray-500 dark:text-gray-400">
                        Atur tanggal libur operasional. Tanggal ini akan diblokir di storefront.
                    </p>
                </div>
            </div>

            <div class="fi-section-content border-t border-gray-100 dark:border-white/10 p-6 space-y-6">

                {{-- Calendar + Form --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    {{-- Flatpickr Inline Calendar --}}
                    <div>
                        <label class="fi-fo-field-wrp-label flex items-center gap-x-3 mb-2">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Pilih Rentang Tanggal
                            </span>
                        </label>
                        <div id="holiday-calendar"></div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400" x-show="selectedRange">
                            Dipilih: <span class="font-medium text-gray-700 dark:text-gray-300" x-text="selectedRange"></span>
                        </p>
                    </div>

                    {{-- Name Input + Add Button --}}
                    <div class="flex flex-col justify-start gap-4">
                        <div>
                            <label class="fi-fo-field-wrp-label flex items-center gap-x-3 mb-2">
                                <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                    Nama Holiday <span class="text-red-500">*</span>
                                </span>
                            </label>
                            <input
                                type="text"
                                x-model="newName"
                                placeholder="cth. Lebaran, Natal, Tahun Baru..."
                                class="fi-input block w-full rounded-lg border-0 bg-white py-1.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/20 dark:placeholder:text-gray-500 dark:focus:ring-primary-500"
                            >
                        </div>

                        <div x-show="errorMsg" class="rounded-lg bg-red-50 dark:bg-red-950/20 px-3 py-2 text-sm text-red-600 dark:text-red-400" x-text="errorMsg"></div>

                        <button
                            type="button"
                            @click="addHoliday()"
                            class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 inline-grid rounded-lg fi-btn-color-primary gap-1.5 px-3 py-2 text-sm shadow-sm bg-primary-600 text-white hover:bg-primary-500 focus-visible:ring-primary-500/50 dark:bg-primary-500 dark:hover:bg-primary-400"
                        >
                            Tambah Holiday
                        </button>

                        {{-- Holiday List --}}
                        <div class="mt-2 space-y-2" x-show="holidays.length > 0">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Holiday Terdaftar:</p>
                            <template x-for="(h, i) in holidays" :key="i">
                                <div class="flex items-center justify-between rounded-lg border border-gray-200 dark:border-white/10 bg-gray-50 dark:bg-white/5 px-3 py-2">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="h.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <span x-text="h.start_date"></span>
                                            <span x-show="h.start_date !== h.end_date"> &ndash; <span x-text="h.end_date"></span></span>
                                        </p>
                                    </div>
                                    <button
                                        type="button"
                                        @click="removeHoliday(i)"
                                        class="ml-3 rounded-md p-1 text-gray-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-950/30 transition"
                                        title="Hapus"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <div x-show="holidays.length === 0" class="rounded-lg border border-dashed border-gray-200 dark:border-white/10 px-4 py-6 text-center">
                            <p class="text-sm text-gray-400 dark:text-gray-500">Belum ada holiday terdaftar.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <x-filament::button type="submit">
                Save Settings
            </x-filament::button>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        function scheduleManager(initialSchedule) {
            const dayNames = {
                '1': 'Senin', '2': 'Selasa', '3': 'Rabu',
                '4': 'Kamis', '5': 'Jumat', '6': 'Sabtu', '0': 'Minggu'
            };
            const dayOrder = ['1', '2', '3', '4', '5', '6', '0'];
            const defaultDay = { enabled: false, open: '08:00', close: '17:00', is_24h: false };

            // Ensure all days exist in schedule
            const schedule = {};
            dayOrder.forEach(d => {
                schedule[d] = Object.assign({}, defaultDay, initialSchedule[d] ?? {});
            });

            return {
                schedule,
                dayNames,
                dayOrder,

                toggleDay(day) {
                    this.schedule[day].enabled = !this.schedule[day].enabled;
                    this.sync();
                },

                toggle24h(day) {
                    this.schedule[day].is_24h = !this.schedule[day].is_24h;
                    this.sync();
                },

                sync() {
                    this.$wire.updateSchedule(this.schedule);
                },
            };
        }

        function holidayManager(initialHolidays) {
            return {
                holidays: initialHolidays || [],
                newName: '',
                selectedStart: null,
                selectedEnd: null,
                selectedRange: '',
                errorMsg: '',
                fpInstance: null,

                initCalendar() {
                    this.fpInstance = flatpickr('#holiday-calendar', {
                        inline: true,
                        mode: 'range',
                        dateFormat: 'Y-m-d',
                        minDate: null,
                        onChange: (selectedDates, dateStr) => {
                            if (selectedDates.length === 2) {
                                this.selectedStart = this.formatDate(selectedDates[0]);
                                this.selectedEnd   = this.formatDate(selectedDates[1]);
                                this.selectedRange = this.selectedStart === this.selectedEnd
                                    ? this.selectedStart
                                    : this.selectedStart + ' s/d ' + this.selectedEnd;
                            } else if (selectedDates.length === 1) {
                                this.selectedStart = this.formatDate(selectedDates[0]);
                                this.selectedEnd   = null;
                                this.selectedRange = this.selectedStart;
                            } else {
                                this.selectedStart = null;
                                this.selectedEnd   = null;
                                this.selectedRange = '';
                            }
                            this.errorMsg = '';
                        }
                    });
                },

                formatDate(date) {
                    const year  = date.getFullYear();
                    const month = String(date.getMonth() + 1).padStart(2, '0');
                    const day   = String(date.getDate()).padStart(2, '0');
                    return `${year}-${month}-${day}`;
                },

                addHoliday() {
                    this.errorMsg = '';
                    if (!this.newName.trim()) {
                        this.errorMsg = 'Nama holiday harus diisi.';
                        return;
                    }
                    if (!this.selectedStart) {
                        this.errorMsg = 'Pilih rentang tanggal terlebih dahulu.';
                        return;
                    }

                    const name      = this.newName.trim();
                    const startDate = this.selectedStart;
                    const endDate   = this.selectedEnd || this.selectedStart;

                    // Reset form immediately so UI feels responsive
                    this.newName       = '';
                    this.selectedStart = null;
                    this.selectedEnd   = null;
                    this.selectedRange = '';
                    if (this.fpInstance) this.fpInstance.clear();

                    this.$wire.addHoliday(name, startDate, endDate).then(() => {
                        this.holidays = this.$wire.holidays;
                    });
                },

                removeHoliday(index) {
                    this.$wire.removeHoliday(index).then(() => {
                        this.holidays = this.$wire.holidays;
                    });
                }
            };
        }
    </script>
</x-filament-panels::page>

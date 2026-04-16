<x-filament-panels::page>
    {{-- Fullscreen modal overlay --}}
    <div
        x-data="{ open: true }"
        x-show="open"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm p-4"
        style="margin: 0 !important;"
    >
        <div
            class="relative w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-2xl bg-white shadow-2xl dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10"
            @click.away=""
        >
            {{-- Header --}}
            <div class="sticky top-0 z-10 flex items-center justify-between border-b border-gray-100 bg-white/95 px-6 py-4 backdrop-blur dark:border-white/10 dark:bg-gray-900/95 rounded-t-2xl">
                <div class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary-50 dark:bg-primary-500/10">
                        <x-heroicon-o-rocket-launch class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                            {{ __('admin.setup_wizard.title') }}
                        </h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ __('admin.setup_wizard.step1_description') }}
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    wire:click="skip"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-1.5 rounded-lg px-3 py-2 text-sm font-medium text-gray-500 transition hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-200"
                >
                    <x-heroicon-o-forward class="h-4 w-4" />
                    {{ __('admin.setup_wizard.skip_button') }}
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6">
                <form wire:submit="submit">
                    {{ $this->form }}

                    <div class="mt-6 flex justify-end">
                        <x-filament::button type="submit" size="lg" icon="heroicon-o-check-circle">
                            {{ __('admin.setup_wizard.complete_button') }}
                        </x-filament::button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Operational Schedule for Tab 2 --}}
    <div
        wire:ignore
        id="wizard-operational-schedule"
        class="hidden"
        x-data="wizardScheduleManager(@js($this->operationalSchedule))"
        x-init="$nextTick(() => mountSchedule())"
    >
        <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="fi-section-header flex items-center gap-x-3 overflow-hidden px-6 py-4">
                <div class="grid flex-1 gap-y-1">
                    <h3 class="fi-section-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                        {{ __('admin.setup_wizard.step2_title') }}
                    </h3>
                    <p class="fi-section-description text-sm text-gray-500 dark:text-gray-400">
                        {{ __('admin.setup_wizard.step2_description') }}
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
                                    <td class="py-3 pr-4">
                                        <span class="font-medium text-gray-900 dark:text-white" x-text="dayNames[day]"></span>
                                    </td>
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
                                    <td class="py-3 pr-3">
                                        <input
                                            type="time"
                                            x-model="schedule[day].open"
                                            @change="sync()"
                                            :disabled="!schedule[day].enabled || schedule[day].is_24h"
                                            class="fi-input block w-full rounded-lg border-0 bg-white py-1.5 text-gray-950 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-white/5 dark:text-white dark:ring-white/20 disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                    </td>
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
    </div>

    <script>
        function wizardScheduleManager(initialSchedule) {
            const dayNames = {
                '1': 'Senin', '2': 'Selasa', '3': 'Rabu',
                '4': 'Kamis', '5': 'Jumat', '6': 'Sabtu', '0': 'Minggu'
            };
            const dayOrder = ['1', '2', '3', '4', '5', '6', '0'];
            const defaultDay = { enabled: false, open: '08:00', close: '17:00', is_24h: false };

            const schedule = {};
            dayOrder.forEach(d => {
                schedule[d] = Object.assign({}, defaultDay, initialSchedule[d] ?? {});
            });

            return {
                schedule,
                dayNames,
                dayOrder,

                mountSchedule() {
                    const container = document.getElementById('wizard-schedule-container');
                    const scheduleEl = document.getElementById('wizard-operational-schedule');

                    if (container && scheduleEl) {
                        scheduleEl.classList.remove('hidden');
                        container.appendChild(scheduleEl);
                    }
                },

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
    </script>
</x-filament-panels::page>

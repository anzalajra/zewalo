{{--
    Administration Checklist Stepper (4 Steps)

    Variables:
    - $steps: array of ['key', 'label', 'status' (completed|active|locked)]
    - $waLink: WhatsApp confirmation URL
    - $checklistPdfUrl: signed URL for checklist PDF (nullable on success page)
    - $permitLink: Google Doc permit template URL
    - $rental: Rental model instance
    - $context: 'success' or 'detail'
--}}

<div class="bg-white rounded-lg shadow p-6 mb-6" x-data="adminChecklist()">
    <h3 class="text-lg font-semibold mb-1">Administrasi</h3>
    <p class="text-sm text-gray-500 mb-6">Selesaikan langkah-langkah berikut sebelum pengambilan barang.</p>

    {{-- Desktop: Horizontal Stepper --}}
    <div class="hidden md:block">
        <div class="flex items-start">
            @foreach($steps as $index => $step)
                <div class="flex-1 flex flex-col items-center">
                    {{-- Circle + Line --}}
                    <div class="flex items-center w-full">
                        @if($index > 0)
                            <div class="flex-1 h-0.5 transition-colors duration-300"
                                :class="steps[{{ $index - 1 }}].status === 'completed' ? 'bg-green-500' : 'bg-gray-200'">
                            </div>
                        @else
                            <div class="flex-1"></div>
                        @endif

                        {{-- Circle --}}
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300 text-sm font-bold"
                            :class="{
                                'bg-green-500 text-white': steps[{{ $index }}].status === 'completed',
                                'bg-primary-600 text-white ring-4 ring-primary-100': steps[{{ $index }}].status === 'active',
                                'bg-gray-200 text-gray-400': steps[{{ $index }}].status === 'locked'
                            }">
                            <template x-if="steps[{{ $index }}].status === 'completed'">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="steps[{{ $index }}].status === 'active'">
                                <span>{{ $index + 1 }}</span>
                            </template>
                            <template x-if="steps[{{ $index }}].status === 'locked'">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </template>
                        </div>

                        @if($index < count($steps) - 1)
                            <div class="flex-1 h-0.5 transition-colors duration-300"
                                :class="steps[{{ $index }}].status === 'completed' ? 'bg-green-500' : 'bg-gray-200'">
                            </div>
                        @else
                            <div class="flex-1"></div>
                        @endif
                    </div>

                    {{-- Label --}}
                    <p class="mt-2 text-xs font-medium text-center transition-colors duration-300"
                        :class="{
                            'text-green-600': steps[{{ $index }}].status === 'completed',
                            'text-primary-600': steps[{{ $index }}].status === 'active',
                            'text-gray-400': steps[{{ $index }}].status === 'locked'
                        }">
                        {{ $step['label'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Mobile: Vertical Stepper --}}
    <div class="md:hidden space-y-0">
        @foreach($steps as $index => $step)
            <div class="flex">
                {{-- Circle + Vertical Line --}}
                <div class="flex flex-col items-center mr-4">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 text-xs font-bold transition-all duration-300"
                        :class="{
                            'bg-green-500 text-white': steps[{{ $index }}].status === 'completed',
                            'bg-primary-600 text-white ring-4 ring-primary-100': steps[{{ $index }}].status === 'active',
                            'bg-gray-200 text-gray-400': steps[{{ $index }}].status === 'locked'
                        }">
                        <template x-if="steps[{{ $index }}].status === 'completed'">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        </template>
                        <template x-if="steps[{{ $index }}].status === 'active'">
                            <span>{{ $index + 1 }}</span>
                        </template>
                        <template x-if="steps[{{ $index }}].status === 'locked'">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </template>
                    </div>
                    @if($index < count($steps) - 1)
                        <div class="w-0.5 flex-1 my-1 transition-colors duration-300"
                            :class="steps[{{ $index }}].status === 'completed' ? 'bg-green-500' : 'bg-gray-200'">
                        </div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="pb-6 flex-1">
                    <p class="text-sm font-medium transition-colors duration-300"
                        :class="{
                            'text-green-600': steps[{{ $index }}].status === 'completed',
                            'text-gray-900': steps[{{ $index }}].status === 'active',
                            'text-gray-400': steps[{{ $index }}].status === 'locked'
                        }">
                        {{ $step['label'] }}
                    </p>

                    {{-- Step action (mobile) --}}
                    @include('frontend.partials._checklist-step-action', ['index' => $index, 'step' => $step])
                </div>
            </div>
        @endforeach
    </div>

    {{-- Desktop: Step Actions below stepper --}}
    <div class="hidden md:block mt-6">
        <div class="flex items-start">
            @foreach($steps as $index => $step)
                <div class="flex-1 flex flex-col items-center px-2">
                    @include('frontend.partials._checklist-step-action', ['index' => $index, 'step' => $step])
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function adminChecklist() {
    return {
        steps: @json($steps),
        csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),

        async trackAndOpen(routeUrl, targetUrl, stepIndex) {
            // Open target immediately
            window.open(targetUrl, '_blank');

            // Fire POST to mark completion
            try {
                await fetch(routeUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                });

                // Optimistic update
                this.steps[stepIndex].status = 'completed';

                // Unlock next step if applicable
                if (stepIndex === 1 && this.steps[2].status === 'locked') {
                    this.steps[2].status = 'active';
                }
                if (stepIndex === 2 && this.steps[3].status === 'locked') {
                    this.steps[3].status = 'active';
                }
            } catch (e) {
                // Silently fail — user can retry
            }
        }
    };
}
</script>

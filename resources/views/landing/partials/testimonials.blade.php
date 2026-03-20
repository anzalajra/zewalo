{{-- Testimonials Section --}}
<section id="testimonials" class="py-20 lg:py-28 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-16">
            <span class="inline-block text-sm font-semibold uppercase tracking-wider text-indigo-600 mb-3">{{ __('landing.testimonials.badge') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900">
                {{ __('landing.testimonials.title_prefix') }} <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ __('landing.testimonials.title_highlight') }}</span> {{ __('landing.testimonials.title_suffix') }}
            </h2>
            <p class="mt-4 text-lg text-gray-600">{{ __('landing.testimonials.subtitle') }}</p>
        </div>

        {{-- Stats Row --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-16">
            @foreach([
                ['value' => '2.500+', 'label' => __('landing.testimonials.stat_businesses')],
                ['value' => '15.000+', 'label' => __('landing.testimonials.stat_daily_users')],
                ['value' => '99.9%', 'label' => __('landing.testimonials.stat_uptime')],
                ['value' => '4.9/5', 'label' => __('landing.testimonials.stat_rating')],
            ] as $stat)
            <div class="text-center p-6 rounded-2xl bg-gray-50">
                <div class="text-3xl font-extrabold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ $stat['value'] }}</div>
                <div class="mt-1 text-sm text-gray-500 font-medium">{{ $stat['label'] }}</div>
            </div>
            @endforeach
        </div>

        {{-- Testimonial cards --}}
        <div class="columns-1 md:columns-2 lg:columns-3 gap-6 space-y-6">
            @foreach([
                [
                    'name' => 'Rina Sari',
                    'role' => __('landing.testimonials.role_owner'),
                    'company' => 'Bali Surf Board Rental',
                    'avatar' => 'RS',
                    'color' => 'indigo',
                    'quote' => __('landing.testimonials.quote_rina'),
                    'rating' => 5,
                ],
                [
                    'name' => 'Ahmad Hidayat',
                    'role' => __('landing.testimonials.role_ops_manager'),
                    'company' => 'Prima Alat Berat',
                    'avatar' => 'AH',
                    'color' => 'purple',
                    'quote' => __('landing.testimonials.quote_ahmad'),
                    'rating' => 5,
                ],
                [
                    'name' => 'Dewi Lestari',
                    'role' => __('landing.testimonials.role_ceo'),
                    'company' => 'EventPro Rental',
                    'avatar' => 'DL',
                    'color' => 'emerald',
                    'quote' => __('landing.testimonials.quote_dewi'),
                    'rating' => 5,
                ],
                [
                    'name' => 'Budi Santoso',
                    'role' => __('landing.testimonials.role_owner'),
                    'company' => 'Santoso Car Rental',
                    'avatar' => 'BS',
                    'color' => 'amber',
                    'quote' => __('landing.testimonials.quote_budi'),
                    'rating' => 5,
                ],
                [
                    'name' => 'Maya Putri',
                    'role' => __('landing.testimonials.role_cofounder'),
                    'company' => 'CamLens Studio',
                    'avatar' => 'MP',
                    'color' => 'rose',
                    'quote' => __('landing.testimonials.quote_maya'),
                    'rating' => 4,
                ],
                [
                    'name' => 'Fajar Nugroho',
                    'role' => __('landing.testimonials.role_director'),
                    'company' => 'Nugroho Property',
                    'avatar' => 'FN',
                    'color' => 'cyan',
                    'quote' => __('landing.testimonials.quote_fajar'),
                    'rating' => 5,
                ],
            ] as $testimonial)
            <div class="break-inside-avoid rounded-2xl border border-gray-100 bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-300">
                {{-- Stars --}}
                <div class="flex gap-0.5 mb-4">
                    @for ($i = 0; $i < $testimonial['rating']; $i++)
                    <svg class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                    @for ($i = $testimonial['rating']; $i < 5; $i++)
                    <svg class="w-4 h-4 text-gray-200" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>

                {{-- Quote --}}
                <p class="text-gray-600 text-sm leading-relaxed mb-6">"{{ $testimonial['quote'] }}"</p>

                {{-- Author --}}
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <div class="w-10 h-10 rounded-full bg-{{ $testimonial['color'] }}-100 text-{{ $testimonial['color'] }}-600 flex items-center justify-center font-bold text-sm shrink-0">
                        {{ $testimonial['avatar'] }}
                    </div>
                    <div class="min-w-0">
                        <div class="font-semibold text-gray-900 text-sm">{{ $testimonial['name'] }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $testimonial['role'] }}, {{ $testimonial['company'] }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

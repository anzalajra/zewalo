@php
    $freePlan = isset($plans) && $plans->isNotEmpty() ? $plans->first() : null;
    $basicPlan = isset($plans) && $plans->count() > 1 ? $plans[1] : null;
    $proPlan = isset($plans) && $plans->count() > 2 ? $plans[2] : null;
@endphp

{{-- Pricing Section --}}
<section id="pricing" class="py-20 lg:py-28 bg-gray-50" x-data="{ frequency: 'monthly' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="text-center max-w-3xl mx-auto mb-12">
            <span class="inline-block text-sm font-semibold uppercase tracking-wider text-indigo-600 mb-3">{{ __('landing.pricing.badge') }}</span>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-gray-900">
                {{ __('landing.pricing.title_prefix') }} <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">{{ __('landing.pricing.title_highlight') }}</span>
            </h2>
            <p class="mt-4 text-lg text-gray-600">{{ __('landing.pricing.subtitle') }}</p>
        </div>

        {{-- Frequency Toggle --}}
        <div class="flex justify-center mb-12">
            <div class="inline-flex rounded-full bg-gray-200/80 p-1">
                <button
                    @click="frequency = 'monthly'"
                    :class="frequency === 'monthly' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="relative rounded-full px-5 py-2 text-sm font-semibold transition-all duration-200"
                >
                    {{ __('landing.pricing.monthly') }}
                </button>
                <button
                    @click="frequency = 'yearly'"
                    :class="frequency === 'yearly' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-600 hover:text-gray-900'"
                    class="relative rounded-full px-5 py-2 text-sm font-semibold transition-all duration-200 flex items-center gap-2"
                >
                    {{ __('landing.pricing.yearly') }}
                    <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-bold text-emerald-700">{{ __('landing.pricing.save_17') }}</span>
                </button>
            </div>
        </div>

        {{-- Pricing Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6 max-w-5xl mx-auto">
            {{-- Free Plan --}}
            <div class="relative flex flex-col rounded-2xl border border-gray-200 bg-white p-8 transition-all hover:shadow-lg hover:-translate-y-1 duration-300">
                <h3 class="text-xl font-bold text-gray-900">{{ __('landing.pricing.free_plan_name') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('landing.pricing.free_plan_desc') }}</p>

                <div class="mt-6 flex items-baseline">
                    <span class="text-4xl font-extrabold text-gray-900">{{ $freePlan ? $freePlan['pricing']['formatted_monthly'] : __('landing.pricing.free_price') }}</span>
                </div>

                <ul class="mt-8 space-y-3 flex-1">
                    @foreach([__('landing.pricing.free_feat_1'), __('landing.pricing.free_feat_2'), __('landing.pricing.free_feat_3'), __('landing.pricing.free_feat_4'), __('landing.pricing.free_feat_5'), __('landing.pricing.free_feat_6')] as $feat)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>

                <a href="/register-tenant" class="mt-8 block w-full rounded-xl border-2 border-gray-200 bg-white py-3 text-center text-sm font-semibold text-gray-900 hover:bg-gray-50 transition-colors">
                    {{ __('landing.pricing.free_cta') }}
                </a>
            </div>

            {{-- Basic Plan (Popular) --}}
            <div class="relative flex flex-col rounded-2xl border-2 border-indigo-600 bg-white p-8 shadow-xl shadow-indigo-600/10 transition-all hover:-translate-y-1 duration-300">
                {{-- Popular Badge --}}
                <div class="absolute -top-4 left-1/2 -translate-x-1/2">
                    <span class="inline-flex items-center gap-1 rounded-full bg-indigo-600 px-4 py-1.5 text-xs font-bold text-white shadow-lg">
                        {{ __('landing.pricing.most_popular') }}
                    </span>
                </div>

                <h3 class="text-xl font-bold text-gray-900">{{ __('landing.pricing.basic_plan_name') }}</h3>
                <p class="mt-1 text-sm text-gray-500">{{ __('landing.pricing.basic_plan_desc') }}</p>

                <div class="mt-6 flex items-baseline gap-1">
                    <span class="text-4xl font-extrabold text-gray-900" x-text="frequency === 'monthly' ? '{{ $basicPlan ? $basicPlan['pricing']['formatted_monthly'] : __('landing.pricing.basic_price_monthly') }}' : '{{ $basicPlan ? $basicPlan['pricing']['formatted_yearly'] : __('landing.pricing.basic_price_yearly') }}'">{{ $basicPlan ? $basicPlan['pricing']['formatted_monthly'] : __('landing.pricing.basic_price_monthly') }}</span>
                    <span class="text-sm text-gray-500">{{ __('landing.pricing.per_month') }}</span>
                </div>
                <p x-show="frequency === 'yearly'" x-cloak class="text-xs text-emerald-600 font-medium mt-1">{{ __('landing.pricing.basic_yearly_note') }}</p>

                <ul class="mt-8 space-y-3 flex-1">
                    @foreach([__('landing.pricing.basic_feat_1'), __('landing.pricing.basic_feat_2'), __('landing.pricing.basic_feat_3'), __('landing.pricing.basic_feat_4'), __('landing.pricing.basic_feat_5'), __('landing.pricing.basic_feat_6'), __('landing.pricing.basic_feat_7'), __('landing.pricing.basic_feat_8')] as $feat)
                    <li class="flex items-center gap-3 text-sm text-gray-600">
                        <svg class="w-5 h-5 text-indigo-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>

                <a href="/register-tenant" class="mt-8 block w-full rounded-xl bg-indigo-600 py-3 text-center text-sm font-semibold text-white hover:bg-indigo-700 transition-colors shadow-sm">
                    {{ __('landing.pricing.basic_cta') }}
                    <svg class="inline-block ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>

            {{-- Pro Plan --}}
            <div class="relative flex flex-col rounded-2xl bg-gray-900 text-white p-8 transition-all hover:shadow-lg hover:-translate-y-1 duration-300 sm:col-span-2 xl:col-span-1">
                {{-- Grid bg --}}
                <div class="absolute inset-0 rounded-2xl bg-[linear-gradient(to_right,#4f4f4f2e_1px,transparent_1px),linear-gradient(to_bottom,#4f4f4f2e_1px,transparent_1px)] bg-[size:40px_40px] [mask-image:radial-gradient(ellipse_80%_50%_at_50%_0%,#000_70%,transparent_110%)] pointer-events-none"></div>

                <h3 class="text-xl font-bold relative z-10">{{ __('landing.pricing.pro_plan_name') }}</h3>
                <p class="mt-1 text-sm text-gray-400 relative z-10">{{ __('landing.pricing.pro_plan_desc') }}</p>

                <div class="mt-6 flex items-baseline gap-1 relative z-10">
                    <span class="text-4xl font-extrabold" x-text="frequency === 'monthly' ? '{{ $proPlan ? $proPlan['pricing']['formatted_monthly'] : __('landing.pricing.pro_price_monthly') }}' : '{{ $proPlan ? $proPlan['pricing']['formatted_yearly'] : __('landing.pricing.pro_price_yearly') }}'">{{ $proPlan ? $proPlan['pricing']['formatted_monthly'] : __('landing.pricing.pro_price_monthly') }}</span>
                    <span class="text-sm text-gray-400">{{ __('landing.pricing.per_month') }}</span>
                </div>
                <p x-show="frequency === 'yearly'" x-cloak class="text-xs text-emerald-400 font-medium mt-1 relative z-10">{{ __('landing.pricing.pro_yearly_note') }}</p>

                <ul class="mt-8 space-y-3 flex-1 relative z-10">
                    @foreach([__('landing.pricing.pro_feat_1'), __('landing.pricing.pro_feat_2'), __('landing.pricing.pro_feat_3'), __('landing.pricing.pro_feat_4'), __('landing.pricing.pro_feat_5'), __('landing.pricing.pro_feat_6'), __('landing.pricing.pro_feat_7'), __('landing.pricing.pro_feat_8'), __('landing.pricing.pro_feat_9'), __('landing.pricing.pro_feat_10')] as $feat)
                    <li class="flex items-center gap-3 text-sm text-gray-300">
                        <svg class="w-5 h-5 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        {{ $feat }}
                    </li>
                    @endforeach
                </ul>

                <a href="/register-tenant" class="relative z-10 mt-8 block w-full rounded-xl bg-white py-3 text-center text-sm font-semibold text-gray-900 hover:bg-gray-100 transition-colors">
                    {{ __('landing.pricing.pro_cta') }}
                    <svg class="inline-block ml-1 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>

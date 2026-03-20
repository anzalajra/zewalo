<!DOCTYPE html>

<html lang="id"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>{{ __('landing.pricing_page.page_title') }}</title>
<!-- Tailwind CSS v3 with Plugins -->
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
<!-- Google Fonts: Inter -->
<link href="https://fonts.googleapis.com" rel="preconnect"/>
<link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: '#14B8A6',
            'primary-dark': '#0D9488',
            'neutral-bg': '#F9FAFB',
          },
          fontFamily: {
            sans: ['Inter', 'sans-serif'],
          },
        }
      }
    }
  </script>
<style data-purpose="custom-transitions">
    .toggle-dot {
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .pricing-card {
      transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .pricing-card:hover {
      transform: translateY(-4px);
    }
  </style>
</head>
<body class="font-sans text-slate-900 bg-white">
<!-- BEGIN: MainHeader -->
@include('landing.partials.header')
<!-- END: MainHeader -->
<main>
<!-- BEGIN: PricingHero -->
<section class="py-16 md:py-24 bg-neutral-bg">
<div class="max-w-7xl mx-auto px-4 text-center">
<h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">
          {{ __('landing.pricing_page.hero_title_prefix') }} <span class="text-primary">{{ __('landing.pricing_page.hero_title_highlight') }}</span>
</h1>
<p class="text-lg text-slate-600 max-w-2xl mx-auto mb-12">
          {{ __('landing.pricing_page.hero_subtitle') }}
        </p>
<!-- Billing Toggle -->
<div class="flex items-center justify-center gap-4 mb-12" id="billing-toggle-container">
<span class="text-sm font-medium text-slate-600">{{ __('landing.pricing_page.monthly') }}</span>
<button aria-checked="false" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none bg-primary" id="billing-toggle" role="switch">
<span aria-hidden="true" class="toggle-dot pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out translate-x-0" id="toggle-knob"></span>
</button>
<span class="text-sm font-medium text-slate-600">{{ __('landing.pricing_page.yearly') }} <span class="text-emerald-500 font-bold ml-1">({{ __('landing.pricing_page.save_20') }})</span></span>
</div>
</div>
<!-- Pricing Cards Grid -->
<div class="max-w-7xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-8">
<!-- Free Plan -->
<div class="pricing-card bg-white rounded-2xl p-8 border border-slate-200 shadow-sm flex flex-col">
<div class="mb-8">
<h3 class="text-lg font-bold text-slate-900 mb-2">{{ __('landing.pricing_page.free_plan_name') }}</h3>
<div class="flex items-baseline">
<span class="text-4xl font-extrabold">{{ isset($plans) && $plans->isNotEmpty() ? $plans->first()['pricing']['formatted_monthly'] : __('landing.pricing.free_price') }}</span>
<span class="text-slate-500 ml-1 text-sm">{{ __('landing.pricing_page.per_month') }}</span>
</div>
<p class="text-primary text-sm font-medium mt-2 italic">{{ __('landing.pricing_page.free_forever') }}</p>
</div>
<ul class="space-y-4 mb-8 flex-grow">
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.free_feat_orders') }}
            </li>
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.free_feat_dashboard') }}
            </li>
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.free_feat_staff') }}
            </li>
</ul>
<button class="w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-900 font-bold hover:bg-slate-50 transition-colors">{{ __('landing.pricing_page.free_cta') }}</button>
</div>
<!-- Basic Plan -->
<div class="pricing-card bg-white rounded-2xl p-8 border border-slate-200 shadow-sm flex flex-col relative overflow-hidden">
<div class="mb-8">
<h3 class="text-lg font-bold text-slate-900 mb-2">{{ __('landing.pricing_page.basic_plan_name') }}</h3>
<div class="flex items-baseline">
<span class="text-4xl font-extrabold" data-price-basic="">{{ isset($plans) && $plans->count() > 1 ? $plans[1]['pricing']['formatted_monthly'] : '$19' }}</span>
<span class="text-slate-500 ml-1 text-sm">{{ __('landing.pricing_page.per_month') }}</span>
</div>
<p class="text-primary text-sm font-medium mt-2">{{ __('landing.pricing_page.basic_trial') }}</p>
</div>
<ul class="space-y-4 mb-8 flex-grow">
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.basic_feat_orders') }}
            </li>
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.basic_feat_inventory') }}
            </li>
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.basic_feat_staff') }}
            </li>
</ul>
<button class="w-full py-3 px-4 rounded-xl border-2 border-slate-200 text-slate-900 font-bold hover:bg-slate-50 transition-colors">{{ __('landing.pricing_page.basic_cta') }}</button>
</div>
<!-- Pro Plan -->
<div class="pricing-card bg-white rounded-2xl p-8 border-2 border-primary shadow-xl flex flex-col relative">
<div class="absolute top-0 right-1/2 translate-x-1/2 -translate-y-1/2">
<span class="bg-primary text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1 rounded-full">{{ __('landing.pricing_page.most_popular') }}</span>
</div>
<div class="mb-8">
<h3 class="text-lg font-bold text-slate-900 mb-2">{{ __('landing.pricing_page.pro_plan_name') }}</h3>
<div class="flex items-baseline">
<span class="text-4xl font-extrabold" data-price-pro="">{{ isset($plans) && $plans->count() > 2 ? $plans[2]['pricing']['formatted_monthly'] : '$49' }}</span>
<span class="text-slate-500 ml-1 text-sm">{{ __('landing.pricing_page.per_month') }}</span>
</div>
<p class="text-primary text-sm font-medium mt-2">{{ __('landing.pricing_page.pro_trial') }}</p>
</div>
<ul class="space-y-4 mb-8 flex-grow">
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.pro_feat_enterprise') }}
            </li>
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.pro_feat_inventory') }}
            </li>
<li class="flex items-start text-sm text-slate-600">
<svg class="w-5 h-5 text-primary mr-2 flex-shrink-0" fill="currentColor" viewbox="0 0 20 20"><path clip-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" fill-rule="evenodd"></path></svg>
              {{ __('landing.pricing_page.pro_feat_staff') }}
            </li>
</ul>
<button class="w-full py-3 px-4 rounded-xl bg-primary text-white font-bold hover:bg-primary-dark transition-colors shadow-lg shadow-teal-100">{{ __('landing.pricing_page.pro_cta') }}</button>
</div>
</div>
</section>
<!-- END: PricingHero -->
<!-- BEGIN: ComparisonTable -->
<section class="py-20 bg-white">
<div class="max-w-6xl mx-auto px-4">
<div class="text-center mb-16">
<h2 class="text-3xl font-bold text-slate-900">{{ __('landing.pricing_page.compare_title') }}</h2>
<p class="text-slate-500 mt-2">{{ __('landing.pricing_page.compare_subtitle') }}</p>
</div>
<div class="overflow-x-auto">
<table class="w-full border-collapse">
<thead>
<tr class="text-left">
<th class="py-6 px-4 bg-slate-50/50 rounded-tl-2xl border-b border-slate-200 text-slate-600 font-semibold w-1/3">{{ __('landing.pricing_page.table_feature') }}</th>
<th class="py-6 px-4 bg-slate-50/50 border-b border-slate-200 text-center font-bold text-slate-900">{{ __('landing.pricing_page.free_plan_name') }}</th>
<th class="py-6 px-4 bg-slate-50/50 border-b border-slate-200 text-center font-bold text-slate-900">{{ __('landing.pricing_page.basic_plan_name') }}</th>
<th class="py-6 px-4 bg-slate-50/50 rounded-tr-2xl border-b border-slate-200 text-center font-bold text-slate-900">{{ __('landing.pricing_page.pro_plan_name') }}</th>
</tr>
</thead>
<tbody class="divide-y divide-slate-100">
<!-- Feature: Rental Live Stock -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_rental_live_stock') }}</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: Advanced Inventory -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_advanced_inventory') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: Booking Online -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_booking_online') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: Quotation -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_quotation_invoicing') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: Reports -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_reports') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: Multi-location -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_multi_location') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: API -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700">{{ __('landing.pricing_page.feat_api') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
<!-- Feature: Account Manager -->
<tr>
<td class="py-5 px-4 font-medium text-slate-700 rounded-bl-2xl">{{ __('landing.pricing_page.feat_account_manager') }}</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center text-slate-300">&mdash;</td>
<td class="py-5 px-4 text-center rounded-br-2xl"><span class="text-primary font-bold">&#10003;</span></td>
</tr>
</tbody>
</table>
</div>
</div>
</section>
<!-- END: ComparisonTable -->
<!-- BEGIN: CTA Section -->
<section class="py-20">
<div class="max-w-7xl mx-auto px-4">
<div class="bg-primary rounded-[2rem] p-12 text-center text-white relative overflow-hidden">
<!-- Background Pattern -->
<div class="absolute inset-0 opacity-10 pointer-events-none">
<svg class="w-full h-full" preserveaspectratio="none" viewbox="0 0 100 100">
<rect fill="url(#pattern-dots)" height="100" width="100" x="0" y="0"></rect>
<defs>
<pattern height="10" id="pattern-dots" patternunits="userSpaceOnUse" width="10" x="0" y="0">
<circle cx="2" cy="2" fill="white" r="1"></circle>
</pattern>
</defs>
</svg>
</div>
<h2 class="text-3xl md:text-4xl font-bold mb-6 relative z-10">{{ __('landing.pricing_page.cta_title') }}</h2>
<p class="text-white/80 max-w-2xl mx-auto mb-10 relative z-10">
            {{ __('landing.pricing_page.cta_subtitle') }}
          </p>
<a class="inline-block bg-white text-primary px-10 py-4 rounded-xl font-bold text-lg hover:bg-slate-50 transition-all shadow-xl relative z-10" href="#">{{ __('landing.pricing_page.cta_button') }}</a>
<p class="mt-6 text-sm text-white/70 relative z-10">{{ __('landing.pricing_page.cta_note') }}</p>
</div>
</div>
</section>
<!-- END: CTA Section -->
</main>
<!-- BEGIN: MainFooter -->
@include('landing.partials.footer')
<!-- END: MainFooter -->
<!-- BEGIN: InteractiveScripts -->
<script data-purpose="pricing-logic">
    const toggle = document.getElementById('billing-toggle');
    const knob = document.getElementById('toggle-knob');
    const basicPriceEl = document.querySelector('[data-price-basic]');
    const proPriceEl = document.querySelector('[data-price-pro]');

    const prices = {
      monthly: {
        basic: '{{ isset($plans) && $plans->count() > 1 ? $plans[1]["pricing"]["formatted_monthly"] : "$19" }}',
        pro: '{{ isset($plans) && $plans->count() > 2 ? $plans[2]["pricing"]["formatted_monthly"] : "$49" }}'
      },
      yearly: {
        basic: '{{ isset($plans) && $plans->count() > 1 ? $plans[1]["pricing"]["formatted_yearly"] : "$15" }}',
        pro: '{{ isset($plans) && $plans->count() > 2 ? $plans[2]["pricing"]["formatted_yearly"] : "$39" }}'
      }
    };

    let isYearly = false;

    toggle.addEventListener('click', () => {
      isYearly = !isYearly;

      // Update UI toggle appearance
      if (isYearly) {
        knob.classList.add('translate-x-5');
        knob.classList.remove('translate-x-0');
        basicPriceEl.textContent = prices.yearly.basic;
        proPriceEl.textContent = prices.yearly.pro;
      } else {
        knob.classList.add('translate-x-0');
        knob.classList.remove('translate-x-5');
        basicPriceEl.textContent = prices.monthly.basic;
        proPriceEl.textContent = prices.monthly.pro;
      }
    });
  </script>
<!-- END: InteractiveScripts -->
</body></html>

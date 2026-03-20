<!DOCTYPE html>

<html class="" lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title><?php echo e(__('landing.index.page_title')); ?></title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#14B8A6",
                        "background-light": "#ffffff",
                        "background-dark": "#f8fafc",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }</script>
</head>
<body class="bg-white font-display text-slate-900 antialiased">
<!-- Header / Navbar -->
<?php echo $__env->make('landing.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<!-- Hero Section -->
<section class="relative pt-20 pb-16 lg:pt-32 lg:pb-24 overflow-hidden">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
<div class="max-w-3xl mx-auto">
<h1 class="text-4xl sm:text-6xl font-black tracking-tight mb-6 leading-[1.1]">
                    <?php echo e(__('landing.index.hero_title_prefix')); ?> <span class="text-primary"><?php echo e(__('landing.index.hero_title_highlight')); ?></span>
</h1>
<p class="text-lg sm:text-xl text-slate-600 dark:text-slate-400 mb-10 leading-relaxed">
                    <?php echo e(__('landing.index.hero_subtitle')); ?>

                </p>
<div class="flex flex-col sm:flex-row items-center justify-center gap-4">
<button class="w-full sm:w-auto bg-primary hover:bg-primary/90 text-white text-lg font-bold px-8 py-4 rounded-xl transition-all shadow-xl shadow-primary/25">
                        <?php echo e(__('landing.index.hero_cta_trial')); ?>

                    </button>
<button class="w-full sm:w-auto bg-slate-200 dark:bg-slate-800 hover:bg-slate-300 dark:hover:bg-slate-700 font-semibold px-8 py-4 rounded-xl transition-all">
                        <?php echo e(__('landing.index.hero_cta_demo')); ?>

                    </button>
</div>
</div>
<!-- Hero Illustration Placeholder -->
<div class="mt-16 relative rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-800 shadow-2xl">
<div class="aspect-video w-full bg-gradient-to-br from-slate-100 to-slate-200 dark:from-slate-800 dark:to-background-dark flex items-center justify-center" data-alt="Modern abstract dashboard UI illustration showing rental statistics and calendars">
<span class="material-symbols-outlined text-9xl text-primary/20">dashboard</span>
</div>
</div>
</div>
<!-- Background Decor -->
<div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full pointer-events-none -z-10 opacity-20">
<div class="absolute top-[-10%] left-[-10%] w-[40%] h-[40%] bg-primary rounded-full blur-[120px]"></div>
<div class="absolute bottom-[-10%] right-[-10%] w-[30%] h-[30%] bg-blue-400 rounded-full blur-[100px]"></div>
</div>
</section>
<!-- Features Section -->
<section class="py-24 bg-slate-50/50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-16">
<h2 class="text-4xl font-black mb-4 tracking-tight"><?php echo e(__('landing.index.feat_heading')); ?></h2>
<p class="text-slate-600 max-w-2xl mx-auto"><?php echo e(__('landing.index.feat_subheading')); ?></p>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-6"><!-- Feature 1: Rental live stock -->
<div class="group relative overflow-hidden bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-4xl">inventory_2</span>
</div>
<h3 class="text-xl font-bold mb-3"><?php echo e(__('landing.index.feat_live_stock')); ?></h3>
<p class="text-slate-600 text-sm leading-relaxed">
            <?php echo e(__('landing.index.feat_live_stock_desc')); ?>

        </p>
</div>
</div>
<!-- Feature 2: Advanced inventory management -->
<div class="md:col-span-2 group relative overflow-hidden bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-4xl">settings_suggest</span>
</div>
<h3 class="text-2xl font-bold mb-3"><?php echo e(__('landing.index.feat_inventory')); ?></h3>
<p class="text-slate-600 leading-relaxed max-w-md">
            <?php echo e(__('landing.index.feat_inventory_desc')); ?>

        </p>
</div>
</div>
<!-- Feature 3: Booking Online -->
<div class="group relative overflow-hidden bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-4xl">calendar_month</span>
</div>
<h3 class="text-xl font-bold mb-3"><?php echo e(__('landing.index.feat_booking')); ?></h3>
<p class="text-slate-600 text-sm leading-relaxed">
            <?php echo e(__('landing.index.feat_booking_desc')); ?>

        </p>
</div>
</div>
<!-- Feature 4: Quotation & Invoicing -->
<div class="group relative overflow-hidden bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-4xl">receipt_long</span>
</div>
<h3 class="text-xl font-bold mb-3"><?php echo e(__('landing.index.feat_invoicing')); ?></h3>
<p class="text-slate-600 text-sm leading-relaxed">
            <?php echo e(__('landing.index.feat_invoicing_desc')); ?>

        </p>
</div>
</div>
<!-- Feature 5: Laporan -->
<div class="group relative overflow-hidden bg-white p-8 rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between">
<div>
<div class="w-14 h-14 bg-primary/10 rounded-2xl flex items-center justify-center text-primary mb-6 group-hover:scale-110 transition-transform duration-300">
<span class="material-symbols-outlined text-4xl">analytics</span>
</div>
<h3 class="text-xl font-bold mb-3"><?php echo e(__('landing.index.feat_reports')); ?></h3>
<p class="text-slate-600 text-sm leading-relaxed">
            <?php echo e(__('landing.index.feat_reports_desc')); ?>

        </p>
</div>
</div></div>
</div>
</section>
<!-- Case Studies Section -->
<section class="py-20">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<h2 class="text-3xl font-bold mb-12"><?php echo e(__('landing.index.case_heading')); ?></h2>
<div class="grid md:grid-cols-3 gap-6">
<!-- Case 1 -->
<div class="group cursor-pointer">
<div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 relative">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Professional camera equipment and lenses on a shelf" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAktuZHqGb-hgugBxWlc5aeuJXMrrJthia9WVy8K2adRlpBEcGbuK76Zp9ZckrIIIY1TK5OmJePbiJdm10l_HDHH0pbbXBMJQ92-Mg4yO5Kh8JLtzWgvy3JlPxpdSXQEPVjJO6xpJ0H1iDL_qRghA4nM_X40cWTAI_B5D6UNwgRNjVI_M0VZF4fq9YhLFXZiZeVWHVxXUj8BqxEvr1g7Q_F4tD2Uy94MIQj2cz8kfKF0z2ajUL5KAkrTQcH9wEl4a-KhGMaNMT4mXi-"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="bg-primary text-xs font-bold text-white px-2 py-1 rounded"><?php echo e(__('landing.index.case_badge_photography')); ?></span>
</div>
</div>
<h4 class="text-lg font-bold"><?php echo e(__('landing.index.case_camera_title')); ?></h4>
<p class="text-slate-600 dark:text-slate-400 text-sm"><?php echo e(__('landing.index.case_camera_desc')); ?></p>
</div>
<!-- Case 2 -->
<div class="group cursor-pointer">
<div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 relative">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Camping tents and outdoor gear in nature" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA18_UZWWXMMjBp1Qhkx2mruyIJF1EHLSi3DKGBuDfcWeGAlNTNSccOKaov4T7aP-eep3oYtjoEFeNk2-OVp_quUj1gB6Y2Yi9_gRkfPEtehZtufuoFcP7FLfEibjLx_co1ymk7-t3skZ6dNFg-yFREdnMPAf1WmF-BKo8SeT3knfs_H2FoKSQCLaYvAiXwOOGoDvotFOOtY92TeWvKtOBfQH6mJO1ox_p0O-GnIYdfFXX6gMf17LuUBrqkuL_ResXku2mW_UkkMoFx"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="bg-primary text-xs font-bold text-white px-2 py-1 rounded"><?php echo e(__('landing.index.case_badge_outdoor')); ?></span>
</div>
</div>
<h4 class="text-lg font-bold"><?php echo e(__('landing.index.case_camping_title')); ?></h4>
<p class="text-slate-600 dark:text-slate-400 text-sm"><?php echo e(__('landing.index.case_camping_desc')); ?></p>
</div>
<!-- Case 3 -->
<div class="group cursor-pointer">
<div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 relative">
<img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" data-alt="Fleet of rental cars parked in a lot" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB85f0pFcvnO6uajuesACYuX1NTnyGyH4bTvSERELMkGRKnWuvB05a3wu6jIzVeDGhvgoQ8rykUDNH9IllnTpaT-DnK7sGF9Rs_8S2qqt3shp_2HfF22LcwwGe0WcU5lyunVFY9EeNcEEs5_cmkXjIM21wneQX09AV8GpExvG4YKnqm2-fbJhVmUHFuB1NaXWag2uti0k26zKQk-4h8GvIrjpg6zSOfkqOvla7K3XsbD0e7ngX6B1pf-C9A9rO_B0C5MudEXUevkfYE"/>
<div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
<div class="absolute bottom-4 left-4">
<span class="bg-primary text-xs font-bold text-white px-2 py-1 rounded"><?php echo e(__('landing.index.case_badge_transport')); ?></span>
</div>
</div>
<h4 class="text-lg font-bold"><?php echo e(__('landing.index.case_car_title')); ?></h4>
<p class="text-slate-600 dark:text-slate-400 text-sm"><?php echo e(__('landing.index.case_car_desc')); ?></p>
</div>
</div>
</div>
</section>
<!-- Pricing Section -->
<section class="py-20 bg-slate-50 dark:bg-slate-900/30">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="text-center mb-12">
<h2 class="text-3xl font-bold mb-4"><?php echo e(__('landing.index.pricing_heading')); ?></h2>
<div class="flex items-center justify-center gap-4 mt-6"><span class="text-sm font-medium" id="monthly-label"><?php echo e(__('landing.index.pricing_monthly')); ?></span>
<button class="relative w-12 h-6 bg-slate-200 rounded-full transition-colors" id="pricing-toggle" onclick="togglePricing()">
<span class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform" id="toggle-knob"></span>
</button>
<span class="text-sm font-medium text-slate-500" id="yearly-label"><?php echo e(__('landing.index.pricing_yearly')); ?> <span class="text-green-500 font-bold"><?php echo e(__('landing.index.pricing_yearly_discount')); ?></span></span>
<script>
    function togglePricing() {
        const knob = document.getElementById('toggle-knob');
        const toggle = document.getElementById('pricing-toggle');
        const isYearly = knob.classList.contains('translate-x-6');

        const basicPrice = document.getElementById('price-basic');
        const proPrice = document.getElementById('price-pro');

        if (isYearly) {
            knob.classList.remove('translate-x-6');
            toggle.classList.remove('bg-primary');
            toggle.classList.add('bg-slate-200');
            basicPrice.innerText = '19';
            proPrice.innerText = '49';
        } else {
            knob.classList.add('translate-x-6');
            toggle.classList.remove('bg-slate-200');
            toggle.classList.add('bg-primary');
            basicPrice.innerText = '15';
            proPrice.innerText = '39';
        }
    }
</script></div>
</div>
<div class="grid md:grid-cols-3 gap-8"><!-- Free -->
<div class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 flex flex-col">
<h3 class="text-lg font-bold mb-2"><?php echo e(__('landing.index.pricing_free')); ?></h3>
<div class="flex items-baseline gap-1 mb-2">
<span class="text-4xl font-black">$0</span>
<span class="text-slate-500"><?php echo e(__('landing.index.pricing_per_month')); ?></span>
</div>
<p class="text-sm text-primary font-medium mb-6"><?php echo e(__('landing.index.pricing_free_forever')); ?></p>
<ul class="space-y-4 mb-8 flex-1">
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_free_feat_orders')); ?>

        </li>
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_free_feat_dashboard')); ?>

        </li>
</ul>
<button class="w-full py-3 rounded-lg border-2 border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"><?php echo e(__('landing.index.pricing_cta_start')); ?></button>
</div>
<!-- Basic -->
<div class="bg-white dark:bg-slate-800 p-8 rounded-2xl border border-slate-200 dark:border-slate-700 flex flex-col">
<h3 class="text-lg font-bold mb-2"><?php echo e(__('landing.index.pricing_basic')); ?></h3>
<div class="flex items-baseline gap-1 mb-2">
<span class="text-4xl font-black">$<span id="price-basic">19</span></span>
<span class="text-slate-500"><?php echo e(__('landing.index.pricing_per_month')); ?></span>
</div>
<p class="text-sm text-primary font-medium mb-6"><?php echo e(__('landing.index.pricing_free_trial')); ?></p>
<ul class="space-y-4 mb-8 flex-1">
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_basic_feat_orders')); ?>

        </li>
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_basic_feat_inventory')); ?>

        </li>
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_basic_feat_staff')); ?>

        </li>
</ul>
<button class="w-full py-3 rounded-lg border-2 border-slate-200 dark:border-slate-700 font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"><?php echo e(__('landing.index.pricing_cta_choose')); ?></button>
</div>
<!-- Pro -->
<div class="bg-white dark:bg-slate-800 p-8 rounded-2xl border-2 border-primary shadow-xl relative flex flex-col scale-105 z-10">
<div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-white text-xs font-bold px-4 py-1 rounded-full uppercase tracking-wider"><?php echo e(__('landing.index.pricing_popular')); ?></div>
<h3 class="text-lg font-bold mb-2"><?php echo e(__('landing.index.pricing_pro')); ?></h3>
<div class="flex items-baseline gap-1 mb-2">
<span class="text-4xl font-black text-primary">$<span id="price-pro">49</span></span>
<span class="text-slate-500"><?php echo e(__('landing.index.pricing_per_month')); ?></span>
</div>
<p class="text-sm text-primary font-medium mb-6"><?php echo e(__('landing.index.pricing_free_trial')); ?></p>
<ul class="space-y-4 mb-8 flex-1">
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_pro_feat_enterprise')); ?>

        </li>
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_pro_feat_inventory')); ?>

        </li>
<li class="flex items-center gap-2 text-sm">
<span class="material-symbols-outlined text-primary text-xl">check_circle</span>
            <?php echo e(__('landing.index.pricing_pro_feat_staff')); ?>

        </li>
</ul>
<button class="w-full py-3 rounded-lg bg-primary text-white font-bold hover:bg-primary/90 transition-all shadow-lg shadow-primary/30"><?php echo e(__('landing.index.pricing_cta_trial')); ?></button>
</div></div>
</div>
</section>
<!-- Testimonials Section -->
<section class="py-20">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
<h2 class="text-3xl font-bold mb-12 text-center"><?php echo e(__('landing.index.testimonial_heading')); ?></h2><div class="flex flex-wrap justify-center items-center gap-8 md:gap-16 mb-16 opacity-50 grayscale hover:grayscale-0 transition-all duration-500">
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-3xl">bolt</span>
<span class="font-black text-xl tracking-tighter">TechRent</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-3xl">forest</span>
<span class="font-black text-xl tracking-tighter">OutdoorGo</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-3xl">directions_car</span>
<span class="font-black text-xl tracking-tighter">AutoLease</span>
</div>
<div class="flex items-center gap-2">
<span class="material-symbols-outlined text-3xl">home_repair_service</span>
<span class="font-black text-xl tracking-tighter">CamHire</span>
</div>
</div>
<div class="grid md:grid-cols-3 gap-8">
<!-- Testimonial 1 -->
<div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-2xl italic relative">
<span class="material-symbols-outlined text-primary/20 text-6xl absolute top-4 left-4">format_quote</span>
<p class="relative z-10 text-slate-700 dark:text-slate-300 mb-6">
                        "<?php echo e(__('landing.index.testimonial_1_quote')); ?>"
                    </p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden bg-slate-200">
<img class="w-full h-full object-cover" data-alt="Profile photo of a male business owner" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBE1Nzhx9YN_55O8qfG8t5GQT1-M4EUlDlIBHC7xv5kK9d4uMJYJC4k_EMApJi6OECCyV0sDI3b-c3o9QtkDQAo_Qil9SE2UxPJfY5XLinqw4iP96paFSUHk_Pxv3F-GIX0_9DdPG5zbwxnch18uH048GM-du1Lx3YcCwVtuanXf9WI7rA1JPIpUDcIRI-6PlvkWViXNA2Z7sdc8HyQVt3d0LcYfg6YY5aSilT_Jn07Ay2q3WH6UUu_vywxToGxtHBySPmLszDkW_wT"/>
</div>
<div>
<p class="font-bold text-sm"><?php echo e(__('landing.index.testimonial_1_name')); ?></p>
<p class="text-xs text-slate-500"><?php echo e(__('landing.index.testimonial_1_role')); ?></p>
</div>
</div>
</div>
<!-- Testimonial 2 -->
<div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-2xl italic relative">
<span class="material-symbols-outlined text-primary/20 text-6xl absolute top-4 left-4">format_quote</span>
<p class="relative z-10 text-slate-700 dark:text-slate-300 mb-6">
                        "<?php echo e(__('landing.index.testimonial_2_quote')); ?>"
                    </p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden bg-slate-200">
<img class="w-full h-full object-cover" data-alt="Profile photo of a female entrepreneur" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDCGpNk0L7qVqN6iOfVTYjEH1FTQuMf0_iD_-uWk2dhW5x1oQ9CRLPbH67-joeKC4oC2jCUaj-49CiU1ruNI2r5oI9ewHqOEshlV_imb2lEVgxNSGYNui6ZAhKv_tJVuS3fscTU3Wwt1Zt32MFsNLRDd69QuZWS3BfR-s5pDZ3_UQv5Q1h_m4KhZKEHcFuJRqehognl78vwumoikzrv87TQaMHy8FPo9-TZIjy2n9QMTHKGdrATL7fhD1gFNrZNyqokp1IdYIokOJdJ"/>
</div>
<div>
<p class="font-bold text-sm"><?php echo e(__('landing.index.testimonial_2_name')); ?></p>
<p class="text-xs text-slate-500"><?php echo e(__('landing.index.testimonial_2_role')); ?></p>
</div>
</div>
</div>
<!-- Testimonial 3 -->
<div class="bg-slate-50 dark:bg-slate-800/50 p-8 rounded-2xl italic relative">
<span class="material-symbols-outlined text-primary/20 text-6xl absolute top-4 left-4">format_quote</span>
<p class="relative z-10 text-slate-700 dark:text-slate-300 mb-6">
                        "<?php echo e(__('landing.index.testimonial_3_quote')); ?>"
                    </p>
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-full overflow-hidden bg-slate-200">
<img class="w-full h-full object-cover" data-alt="Profile photo of a male fleet manager" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAkzJymxCW1iJUbw7KHpjLXMiBPo9BSjMn4KY6gVesCHH6_31qZ__w0NKg1I0JRndkecHizf2XLVDmRyglkwqvDMLMYkpnoTmOcrl81JxAq6kI1CDCNU5jHpxguTYEvNo6Y0nrz1hGj2LzfMFcvemxWjjmFUbHenPmCcP6KpoyYV2CVKcyBcSy-HKWTdyj9MK8lakZWhV0Z4ivDsh-1pfQTViNblzjXk6w-8qq7K04UoefiJ2-6mW37wv0esmM5B9u71G2fU8ATcu-3"/>
</div>
<div>
<p class="font-bold text-sm"><?php echo e(__('landing.index.testimonial_3_name')); ?></p>
<p class="text-xs text-slate-500"><?php echo e(__('landing.index.testimonial_3_role')); ?></p>
</div>
</div>
</div>
</div>
</div>
</section>
<!-- Final CTA Section -->
<section class="py-20">
<div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
<div class="bg-primary rounded-3xl p-10 sm:p-16 text-center text-white relative overflow-hidden">
<div class="relative z-10">
<h2 class="text-3xl sm:text-4xl font-black mb-6"><?php echo e(__('landing.index.cta_heading')); ?></h2>
<p class="text-blue-100 text-lg mb-10 max-w-2xl mx-auto">
                        <?php echo e(__('landing.index.cta_description')); ?>

                    </p>
<button class="bg-white text-primary hover:bg-slate-100 text-lg font-bold px-10 py-4 rounded-xl transition-all shadow-xl">
                        <?php echo e(__('landing.index.cta_button')); ?>

                    </button>
<p class="mt-6 text-sm text-blue-200"><?php echo e(__('landing.index.cta_subtext')); ?></p>
</div>
<!-- Abstract pattern background for CTA -->
<div class="absolute inset-0 opacity-10 pointer-events-none">
<svg height="100%" preserveaspectratio="none" viewbox="0 0 100 100" width="100%">
<path d="M0 0 L100 0 L100 100 L0 100 Z" fill="url(#grad1)"></path>
<defs>
<lineargradient id="grad1" x1="0%" x2="100%" y1="0%" y2="100%">
<stop offset="0%" style="stop-color:rgb(255,255,255);stop-opacity:0.2"></stop>
<stop offset="100%" style="stop-color:rgb(255,255,255);stop-opacity:0"></stop>
</lineargradient>
</defs>
</svg>
</div>
</div>
</div>
</section>
<!-- Footer -->
<?php echo $__env->make('landing.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body></html>
<?php /**PATH /var/www/resources/views/landing/index.blade.php ENDPATH**/ ?>
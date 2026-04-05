<!DOCTYPE html>

<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title><?php echo e(__('landing.sol_outdoor.page_title')); ?></title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<style>[x-cloak] { display: none !important; }</style>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#14B8A6",
                        "background-light": "#f6f8f8",
                        "background-dark": "#11211f",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: { "DEFAULT": "0.25rem", "lg": "0.5rem", "xl": "0.75rem", "full": "9999px" },
                },
            },
        }
    </script>
</head>

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100">
    <!-- BEGIN: MainHeader -->
    <?php echo $__env->make('landing.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- END: MainHeader -->

    <div class="relative flex h-auto min-h-screen w-full flex-col group/design-root overflow-x-hidden">
        <div class="layout-container flex h-full grow flex-col">
            <div class="px-4 md:px-20 lg:px-40 flex flex-1 justify-center py-10">
                <div class="layout-content-container flex flex-col max-w-[960px] flex-1">
                    <!-- Hero / Page Heading Section -->
                    <div class="flex flex-wrap justify-between gap-3 p-4">
                        <div class="flex min-w-72 flex-col gap-3">
                            <h1 class="text-slate-900 dark:text-slate-100 text-4xl font-black leading-tight tracking-[-0.033em]"><?php echo e(__('landing.sol_outdoor.hero_title')); ?></h1>
                            <p class="text-slate-600 dark:text-slate-400 text-base font-normal leading-normal"><?php echo e(__('landing.sol_outdoor.hero_description')); ?></p>
                        </div>
                    </div>
                    <!-- Stats / Metrics -->
                    <div class="flex flex-wrap gap-4 p-4">
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-primary/20 bg-white dark:bg-slate-800">
                            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium leading-normal"><?php echo e(__('landing.sol_outdoor.stat1_label')); ?></p>
                            <p class="text-primary tracking-light text-2xl font-bold leading-tight"><?php echo e(__('landing.sol_outdoor.stat1_value')); ?></p>
                            <p class="text-emerald-600 dark:text-emerald-400 text-xs font-medium leading-normal"><?php echo e(__('landing.sol_outdoor.stat1_note')); ?></p>
                        </div>
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-primary/20 bg-white dark:bg-slate-800">
                            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium leading-normal"><?php echo e(__('landing.sol_outdoor.stat2_label')); ?></p>
                            <p class="text-primary tracking-light text-2xl font-bold leading-tight"><?php echo e(__('landing.sol_outdoor.stat2_value')); ?></p>
                            <p class="text-emerald-600 dark:text-emerald-400 text-xs font-medium leading-normal"><?php echo e(__('landing.sol_outdoor.stat2_note')); ?></p>
                        </div>
                        <div class="flex min-w-[158px] flex-1 flex-col gap-2 rounded-lg p-6 border border-primary/20 bg-white dark:bg-slate-800">
                            <p class="text-slate-600 dark:text-slate-400 text-sm font-medium leading-normal"><?php echo e(__('landing.sol_outdoor.stat3_label')); ?></p>
                            <p class="text-primary tracking-light text-2xl font-bold leading-tight"><?php echo e(__('landing.sol_outdoor.stat3_value')); ?></p>
                            <p class="text-emerald-600 dark:text-emerald-400 text-xs font-medium leading-normal"><?php echo e(__('landing.sol_outdoor.stat3_note')); ?></p>
                        </div>
                    </div>
                    <!-- Inventory Table Section -->
                    <h2 class="text-slate-900 dark:text-slate-100 text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5"><?php echo e(__('landing.sol_outdoor.table_title')); ?></h2>
                    <div class="px-4 py-3 @container">
                        <div class="flex overflow-hidden rounded-lg border border-primary/20 bg-white dark:bg-slate-800">
                            <table class="flex-1 text-left border-collapse">
                                <thead>
                                    <tr class="bg-slate-50 dark:bg-slate-700/50">
                                        <th class="px-4 py-3 text-slate-900 dark:text-slate-100 text-sm font-semibold"><?php echo e(__('landing.sol_outdoor.table_col_equipment')); ?></th>
                                        <th class="px-4 py-3 text-slate-900 dark:text-slate-100 text-sm font-semibold"><?php echo e(__('landing.sol_outdoor.table_col_status')); ?></th>
                                        <th class="px-4 py-3 text-slate-900 dark:text-slate-100 text-sm font-semibold"><?php echo e(__('landing.sol_outdoor.table_col_last_service')); ?></th>
                                        <th class="px-4 py-3 text-slate-900 dark:text-slate-100 text-sm font-semibold"><?php echo e(__('landing.sol_outdoor.table_col_stock')); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-t border-slate-100 dark:border-slate-700">
                                        <td class="px-4 py-4 text-slate-700 dark:text-slate-300 text-sm"><?php echo e(__('landing.sol_outdoor.table_row1_name')); ?></td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400"><?php echo e(__('landing.sol_outdoor.status_available')); ?></span>
                                        </td>
                                        <td class="px-4 py-4 text-slate-500 dark:text-slate-400 text-sm"><?php echo e(__('landing.sol_outdoor.table_row1_service')); ?></td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-20 h-1.5 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                                    <div class="bg-primary h-full" style="width: 85%;"></div>
                                                </div>
                                                <span class="text-sm font-medium">42/50</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-t border-slate-100 dark:border-slate-700">
                                        <td class="px-4 py-4 text-slate-700 dark:text-slate-300 text-sm"><?php echo e(__('landing.sol_outdoor.table_row2_name')); ?></td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-400"><?php echo e(__('landing.sol_outdoor.status_cleaning')); ?></span>
                                        </td>
                                        <td class="px-4 py-4 text-slate-500 dark:text-slate-400 text-sm"><?php echo e(__('landing.sol_outdoor.table_row2_service')); ?></td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-20 h-1.5 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                                    <div class="bg-primary h-full" style="width: 20%;"></div>
                                                </div>
                                                <span class="text-sm font-medium">12/60</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="border-t border-slate-100 dark:border-slate-700">
                                        <td class="px-4 py-4 text-slate-700 dark:text-slate-300 text-sm"><?php echo e(__('landing.sol_outdoor.table_row3_name')); ?></td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300"><?php echo e(__('landing.sol_outdoor.status_out_on_rent')); ?></span>
                                        </td>
                                        <td class="px-4 py-4 text-slate-500 dark:text-slate-400 text-sm"><?php echo e(__('landing.sol_outdoor.table_row3_service')); ?></td>
                                        <td class="px-4 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-20 h-1.5 rounded-full bg-slate-100 dark:bg-slate-700 overflow-hidden">
                                                    <div class="bg-primary h-full" style="width: 5%;"></div>
                                                </div>
                                                <span class="text-sm font-medium">4/80</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Features Grid -->
                    <div class="flex flex-col gap-10 px-4 py-12">
                        <div class="flex flex-col gap-4">
                            <h2 class="text-slate-900 dark:text-slate-100 tracking-light text-3xl font-bold leading-tight"><?php echo e(__('landing.sol_outdoor.features_title')); ?></h2>
                            <p class="text-slate-600 dark:text-slate-400 text-base max-w-[720px]"><?php echo e(__('landing.sol_outdoor.features_description')); ?></p>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="flex flex-col gap-4 rounded-xl border border-primary/10 bg-white dark:bg-slate-800/50 p-6">
                                <span class="material-symbols-outlined text-primary text-3xl">sync</span>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold"><?php echo e(__('landing.sol_outdoor.feature1_title')); ?></h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm"><?php echo e(__('landing.sol_outdoor.feature1_description')); ?></p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-4 rounded-xl border border-primary/10 bg-white dark:bg-slate-800/50 p-6">
                                <span class="material-symbols-outlined text-primary text-3xl">build_circle</span>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold"><?php echo e(__('landing.sol_outdoor.feature2_title')); ?></h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm"><?php echo e(__('landing.sol_outdoor.feature2_description')); ?></p>
                                </div>
                            </div>
                            <div class="flex flex-col gap-4 rounded-xl border border-primary/10 bg-white dark:bg-slate-800/50 p-6">
                                <span class="material-symbols-outlined text-primary text-3xl">event_available</span>
                                <div class="flex flex-col gap-1">
                                    <h3 class="text-slate-900 dark:text-slate-100 text-lg font-bold"><?php echo e(__('landing.sol_outdoor.feature3_title')); ?></h3>
                                    <p class="text-slate-600 dark:text-slate-400 text-sm"><?php echo e(__('landing.sol_outdoor.feature3_description')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial Section -->
                    <div class="px-4 py-12">
                        <div class="rounded-2xl bg-primary/10 p-8 flex flex-col md:flex-row gap-8 items-center">
                            <div class="w-24 h-24 rounded-full overflow-hidden flex-shrink-0 border-4 border-white dark:border-slate-800 shadow-lg">
                                <img alt="<?php echo e(__('landing.sol_outdoor.testimonial_image_alt')); ?>" class="w-full h-full object-cover" data-alt="<?php echo e(__('landing.sol_outdoor.testimonial_image_alt')); ?>" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCSJIpsEpn1_hBwqADguADsTuU2SUj5iMjmUAXEUyjM4v_t6SwhY8dzmXZwn9yza3znV0Uys9NZkG9XWO6-BRp2D9_q91xTGunLMitpvyDjZXFALfN6shM8V5YvXrC3l0CcPOj7eAd1oWUYaQl89jPr7xfxqjK43mNlGZKsPQOwtx46Qz5fsf6TFTvguhaXg_q4bckslD-UIy1q-ElSdWTNjIAVChenAZgQQUgI2IuFZJppwsL9vifBZYjzmfb0UixrGrTEBVs844Uz" />
                            </div>
                            <div class="flex flex-col gap-4">
                                <div class="flex text-primary">
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                    <span class="material-symbols-outlined fill-1">star</span>
                                </div>
                                <blockquote class="text-xl font-medium italic text-slate-800 dark:text-slate-200">
                                    "<?php echo e(__('landing.sol_outdoor.testimonial_quote')); ?>"
                                </blockquote>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-slate-100"><?php echo e(__('landing.sol_outdoor.testimonial_name')); ?></p>
                                    <p class="text-sm text-slate-600 dark:text-slate-400"><?php echo e(__('landing.sol_outdoor.testimonial_role')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- FAQ Section -->
                    <div class="px-4 py-12">
                        <h2 class="text-2xl font-bold mb-8 text-center"><?php echo e(__('landing.sol_outdoor.faq_title')); ?></h2>
                        <div class="space-y-4 max-w-2xl mx-auto">
                            <div class="border border-primary/20 rounded-lg bg-white dark:bg-slate-800 overflow-hidden">
                                <div class="px-6 py-4 flex justify-between items-center cursor-pointer">
                                    <span class="font-semibold"><?php echo e(__('landing.sol_outdoor.faq1_question')); ?></span>
                                    <span class="material-symbols-outlined text-primary">expand_more</span>
                                </div>
                                <div class="px-6 pb-4 text-slate-600 dark:text-slate-400 text-sm">
                                    <?php echo e(__('landing.sol_outdoor.faq1_answer')); ?>

                                </div>
                            </div>
                            <div class="border border-primary/20 rounded-lg bg-white dark:bg-slate-800 overflow-hidden">
                                <div class="px-6 py-4 flex justify-between items-center cursor-pointer">
                                    <span class="font-semibold"><?php echo e(__('landing.sol_outdoor.faq2_question')); ?></span>
                                    <span class="material-symbols-outlined text-primary">expand_more</span>
                                </div>
                                <div class="px-6 pb-4 text-slate-600 dark:text-slate-400 text-sm">
                                    <?php echo e(__('landing.sol_outdoor.faq2_answer')); ?>

                                </div>
                            </div>
                            <div class="border border-primary/20 rounded-lg bg-white dark:bg-slate-800 overflow-hidden">
                                <div class="px-6 py-4 flex justify-between items-center cursor-pointer">
                                    <span class="font-semibold"><?php echo e(__('landing.sol_outdoor.faq3_question')); ?></span>
                                    <span class="material-symbols-outlined text-primary">expand_more</span>
                                </div>
                                <div class="px-6 pb-4 text-slate-600 dark:text-slate-400 text-sm">
                                    <?php echo e(__('landing.sol_outdoor.faq3_answer')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- CTA Section -->
                    <div class="px-4 py-12">
                        <div class="bg-primary rounded-2xl p-10 text-center flex flex-col items-center gap-6">
                            <h2 class="text-white text-3xl md:text-4xl font-black leading-tight"><?php echo e(__('landing.sol_outdoor.cta_title')); ?></h2>
                            <p class="text-white/90 text-lg max-w-xl"><?php echo e(__('landing.sol_outdoor.cta_description')); ?></p>
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="/register-tenant" class="px-8 py-4 bg-white text-primary font-bold rounded-xl shadow-lg hover:bg-slate-50 transition-colors"><?php echo e(__('landing.sol_outdoor.cta_trial')); ?></a>
                                <a href="/contact" class="px-8 py-4 bg-primary/20 text-white border border-white/40 font-bold rounded-xl hover:bg-white/10 transition-colors"><?php echo e(__('landing.sol_outdoor.cta_demo')); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: MainFooter -->
    <?php echo $__env->make('landing.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- END: MainFooter -->
</body>

</html>
<?php /**PATH /var/www/resources/views/landing/solutions/outdoor-camping.blade.php ENDPATH**/ ?>
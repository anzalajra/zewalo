<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Zewalo Features - Comprehensive Inventory &amp; Rental Tools</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap"
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
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 font-display">
    <!-- BEGIN: MainHeader -->
    <?php echo $__env->make('landing.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- END: MainHeader -->

    <div class="relative flex min-h-screen w-full flex-col overflow-x-hidden">
        <main class="flex flex-1 justify-center py-12 px-6 lg:px-40">
            <div class="layout-content-container flex flex-col max-w-[1200px] flex-1 gap-12">
                <!-- Hero Section -->
                <div class="flex flex-col gap-4 p-4">
                    <div class="flex flex-col gap-3">
                        <h1 class="text-slate-900 dark:text-slate-100 text-5xl font-black leading-tight tracking-tight">
                            Zewalo Features</h1>
                        <p class="text-slate-600 dark:text-slate-400 text-lg font-normal max-w-2xl">
                            Everything you need to manage your rental business and inventory in one powerful platform.
                            Explore our comprehensive suite of professional tools.
                        </p>
                    </div>
                </div>
                <!-- Features Grid Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
                    <!-- Feature Card 1 -->
                    <div
                        class="group flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 transition-all hover:shadow-lg hover:border-primary/50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-3xl">monitoring</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h2 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">Live
                                Inventory Stock</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Never lose track of your assets again. Real-time updates across multiple locations with
                                instant sync.
                            </p>
                        </div>
                        <a class="mt-auto text-primary text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all"
                            href="#live-inventory">
                            Details <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                    <!-- Feature Card 2 -->
                    <div
                        class="group flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 transition-all hover:shadow-lg hover:border-primary/50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-3xl">inventory_2</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h2 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">Advanced
                                Management</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Complex batch tracking, serial number management, and maintenance scheduling made
                                simple.
                            </p>
                        </div>
                        <a class="mt-auto text-primary text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all"
                            href="#advanced-management">
                            Details <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                    <!-- Feature Card 3 -->
                    <div
                        class="group flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 transition-all hover:shadow-lg hover:border-primary/50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-3xl">calendar_month</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h2 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">Online
                                Booking</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Let your customers book directly from your website with a seamless 24/7 reservation
                                experience.
                            </p>
                        </div>
                        <a class="mt-auto text-primary text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all"
                            href="#online-booking">
                            Details <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                    <!-- Feature Card 4 -->
                    <div
                        class="group flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 transition-all hover:shadow-lg hover:border-primary/50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-3xl">receipt_long</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h2 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">Quoting &amp;
                                Invoicing</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Automated billing, professional quotes, and digital signatures to accelerate your sales
                                cycle.
                            </p>
                        </div>
                        <a class="mt-auto text-primary text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all"
                            href="#quotation-invoicing">
                            Details <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                    <!-- Feature Card 5 -->
                    <div
                        class="group flex flex-col gap-4 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 p-6 transition-all hover:shadow-lg hover:border-primary/50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <span class="material-symbols-outlined text-3xl">query_stats</span>
                        </div>
                        <div class="flex flex-col gap-2">
                            <h2 class="text-slate-900 dark:text-slate-100 text-xl font-bold leading-tight">Financial
                                Reports</h2>
                            <p class="text-slate-600 dark:text-slate-400 text-sm leading-relaxed">
                                Deep insights into asset performance, ROI, and revenue trends with customizable
                                reporting.
                            </p>
                        </div>
                        <a class="mt-auto text-primary text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all"
                            href="#financial-reports">
                            Details <span class="material-symbols-outlined text-xs">arrow_forward</span>
                        </a>
                    </div>
                </div>
                <!-- Detailed Feature Sections -->
                <div class="flex flex-col gap-16 px-4 pt-10">
                    <h2
                        class="text-slate-900 dark:text-slate-100 text-3xl font-bold border-b border-slate-200 dark:border-slate-800 pb-4">
                        Detailed Insights</h2>
                    <!-- Live Inventory Stock Detail -->
                    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" id="live-inventory">
                        <div class="order-2 lg:order-1 flex flex-col gap-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-4xl">live_tv</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-2xl font-bold">Live Inventory Stock
                                </h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Get a bird's-eye view of your entire operation. Our live inventory engine tracks every
                                item's status—whether it's on a shelf, in transit, or currently at a customer's
                                location.
                            </p>
                            <ul class="flex flex-col gap-3">
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Real-time availability alerts
                                </li>
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Multi-warehouse support
                                </li>
                            </ul>
                            <div>
                                <button
                                    class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    Learn More about Live Tracking
                                </button>
                            </div>
                        </div>
                        <div
                            class="order-1 lg:order-2 rounded-2xl overflow-hidden shadow-2xl bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="Dashboard showing real-time inventory levels and warehouse metrics"
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAoTdka9SCcBDZxOkOKkDtQlR2gact0B6NeO2is9ROxtV8dl_XBrOK7pRTYPIEVU7H7S8mel46nxzl9GhVLMB3bI5ZEy1u7Bb5V2KoyXAw2db8_VagjCTij9Hzqp6Gxjj_j5DboU8HMsG7yspf8DeM_2fewBDJRPem7An9SHYCMcgPMGF4SIufNfWbQGa1pwpfjEK0R9qdQ-mNq03urbHs1jANyfOXMYBT9gVlw3alwgdrIgNgUMg7SGVTau6C4Mis5_rw4Pl9jlsfq');">
                            </div>
                        </div>
                    </section>
                    <!-- Advanced Management Detail -->
                    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" id="advanced-management">
                        <div
                            class="rounded-2xl overflow-hidden shadow-2xl bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="Close up of organized industrial warehouse inventory"
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCKl8pUHp7s_5eWsTSGqS3MUVTqL2DPJiQp55NAlMwIPb52XpSO5zYDtv5AK5QcZPkhDno_n4DCEFiJQR231K3KPEd_RTkwsazbg4YrgORKtMae9k4hVOnrfpvpPFBGqiLSdJ4ieREmuPe7yzVUT6VcB1KPARCgzsHnotVo8DzraLVJRukcjaLyTqEppYVTQCyW2mY1B6-apWvdtiQviWNUppoLGUREb4yIL8akPWCO5uGA-Ug4RHZZeFxhwskANJloHh5U18UtA8hj');">
                            </div>
                        </div>
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-4xl">settings_suggest</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-2xl font-bold">Advanced Inventory
                                    Management</h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Take control of complex logistics. Zewalo's advanced suite allows for granular tracking
                                of individual assets through their entire lifecycle, from procurement to retirement.
                            </p>
                            <ul class="flex flex-col gap-3">
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Preventative maintenance scheduling
                                </li>
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Serial number and batch tracking
                                </li>
                            </ul>
                            <div>
                                <button
                                    class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    Explore Advanced Tools
                                </button>
                            </div>
                        </div>
                    </section>
                    <!-- Booking Online Detail -->
                    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" id="online-booking">
                        <div class="order-2 lg:order-1 flex flex-col gap-6">
                            <div class="flex items-center gap-3">
                                <span
                                    class="material-symbols-outlined text-primary text-4xl">shopping_cart_checkout</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-2xl font-bold">Booking Online</h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Empower your customers to rent on their terms. Our white-label booking portal integrates
                                directly into your existing website, providing a smooth checkout experience.
                            </p>
                            <ul class="flex flex-col gap-3">
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Real-time availability calendar
                                </li>
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Secure online payment processing
                                </li>
                            </ul>
                            <div>
                                <button
                                    class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    Setup Online Booking
                                </button>
                            </div>
                        </div>
                        <div
                            class="order-1 lg:order-2 rounded-2xl overflow-hidden shadow-2xl bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="Modern clean e-commerce interface on a digital screen"
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCHK7AsQhg-7LLHCUoh4fGb_m9xOADUQd0-gGc-G5BxJKAtNioFotf9MZTzuO__b1icfCrvaP91-ck9MjbgJ1sSx3vbrbrfQi4DMOka8lSw7HYRuT4LJJxAMlRsPWOlvdFTn6IhoxWPIpQ9xOSQtwU_j9ZRI91O6CIzye7e671hDfATX3DH3GNIf8g84gOajKjSoHxiwAzUWTu2FM8QEYsDQG4tvgZsBUUD6ucXqQLrIl3ajyYHLJcOvAwKQywZodrkHrzGccPe5sqE');">
                            </div>
                        </div>
                    </section>
                    <!-- Quotation & Invoicing Detail -->
                    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" id="quotation-invoicing">
                        <div
                            class="rounded-2xl overflow-hidden shadow-2xl bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="Professional invoice and accounting documents on a desk"
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCVzHroxgYJUHoIQ3SWzgpbJEiPUP-BJSy6U-xcj1V_rmlMQNb77G-u464aIpkSOggEUgCgZygjhZGPnRn0LbIuQXgJKQBJbTLUWsjXKb04PMg-mqlgZOwWe3o9VXeOjdN5CeNmbdm8sZfKFOrwWcGZrd1xjR07s64xJp4OAUAVCzr-QWk53KVS3ogRiWUmNJoFt6_keVXT1yrKNSYIG7Jo-3RLJVr9FJlKnBuGouSsIOq1HjXnJFl-KGXUbvFTpjBZ-u1hxcv31RCl');">
                            </div>
                        </div>
                        <div class="flex flex-col gap-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-4xl">description</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-2xl font-bold">Quotation &amp;
                                    Invoicing</h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Professionalism at every touchpoint. Quickly generate branded quotes that convert into
                                rental orders with a single click. Manage deposits, partial payments, and overdue
                                reminders.
                            </p>
                            <ul class="flex flex-col gap-3">
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Automated recurring billing
                                </li>
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Digital signature integration
                                </li>
                            </ul>
                            <div>
                                <button
                                    class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    Streamline Billing
                                </button>
                            </div>
                        </div>
                    </section>
                    <!-- Rental & Financial Reports Detail -->
                    <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center" id="financial-reports">
                        <div class="order-2 lg:order-1 flex flex-col gap-6">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-primary text-4xl">bar_chart_4_bars</span>
                                <h3 class="text-slate-900 dark:text-slate-100 text-2xl font-bold">Rental &amp; Financial
                                    Reports</h3>
                            </div>
                            <p class="text-slate-600 dark:text-slate-400 text-lg leading-relaxed">
                                Turn data into decisions. Our robust reporting engine provides crystal-clear visibility
                                into your business's financial health and asset performance.
                            </p>
                            <ul class="flex flex-col gap-3">
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Utilization and ROI reports
                                </li>
                                <li class="flex items-center gap-2 text-slate-700 dark:text-slate-300">
                                    <span class="material-symbols-outlined text-primary">check_circle</span>
                                    Tax and revenue forecasting
                                </li>
                            </ul>
                            <div>
                                <button
                                    class="bg-primary hover:bg-primary/90 text-white font-bold py-3 px-8 rounded-lg transition-colors">
                                    View Reporting Suite
                                </button>
                            </div>
                        </div>
                        <div
                            class="order-1 lg:order-2 rounded-2xl overflow-hidden shadow-2xl bg-slate-100 dark:bg-slate-800 aspect-video flex items-center justify-center">
                            <div class="w-full h-full bg-cover bg-center"
                                data-alt="Financial growth charts and analytical graphs on a monitor"
                                style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuAGKo6D3KLNspGmyCJL9-QUN-SUHSbrz-a31mY9Dpn3HAVbQnPArjTOtL3aLKh-zcgoTT6RM0d7QyUvACo9w9suvoMX8nqt9PPnCu7YUzXj96sXMk8mrA55MQe2LpcHTC3BTEqJNHjCq0_TfiZXg2QW8Q0kNa9ydt1R1Yrp_xFRaBw390rGYaDLN5HspfLQwSYWF-jkxj7-wwF7fBDK8PpihvRAm38Vz_V4YndmI2mkpO08-YSCDGvX7BuQMwHW29wVO7Edjk19NLmW');">
                            </div>
                        </div>
                    </section>
                </div>
                <!-- Call to Action Section -->
                <div class="p-4 mt-8">
                    <div
                        class="rounded-3xl bg-primary/10 dark:bg-primary/5 p-12 text-center flex flex-col items-center gap-6">
                        <h2 class="text-3xl font-bold text-slate-900 dark:text-slate-100">Ready to transform your rental
                            business?</h2>
                        <p class="text-slate-600 dark:text-slate-400 max-w-xl">
                            Join thousands of companies using Zewalo to manage their inventory and grow their revenue.
                        </p>
                        <div class="flex flex-wrap justify-center gap-4">
                            <button
                                class="bg-primary hover:bg-primary/90 text-white font-bold py-4 px-10 rounded-xl transition-all shadow-lg hover:scale-105">
                                Start Free Trial
                            </button>
                            <button
                                class="bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 border border-slate-200 dark:border-slate-800 font-bold py-4 px-10 rounded-xl transition-all hover:bg-slate-50 dark:hover:bg-slate-800">
                                Schedule Demo
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- BEGIN: MainFooter -->
    <?php echo $__env->make('landing.partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <!-- END: MainFooter -->
</body>

</html><?php /**PATH /var/www/resources/views/landing/feature.blade.php ENDPATH**/ ?>
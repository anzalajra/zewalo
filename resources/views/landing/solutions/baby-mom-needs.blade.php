<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Zewalo - Premium Baby & Gear Rentals Solution</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&amp;display=swap"
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

<body class="font-display bg-background-light dark:bg-background-dark text-slate-900 dark:text-slate-100 antialiased">
    <!-- BEGIN: MainHeader -->
    @include('landing.partials.header')
    <!-- END: MainHeader -->

    <!-- Hero Section -->
    <section class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-20">
            <div class="flex flex-col lg:flex-row items-center gap-12">
                <div class="flex-1 space-y-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-primary/10 text-primary">
                        Trusted by 10,000+ Moms
                    </span>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black tracking-tight leading-tight">
                        Premium Baby &amp; <span class="text-primary">Gear Rentals</span>
                    </h1>
                    <p class="text-lg text-slate-600 dark:text-slate-400 max-w-2xl">
                        Safe, hygienic, and age-appropriate solutions for your little ones. We handle the heavy lifting
                        and the deep cleaning so you can focus on the memories.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="/register-tenant"
                            class="bg-primary hover:bg-primary/90 text-white px-8 py-4 rounded-xl font-bold transition-all shadow-lg shadow-primary/25">
                            Rent Now
                        </a>
                        <a href="/contact"
                            class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 px-8 py-4 rounded-xl font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined">play_circle</span>
                            How it Works
                        </a>
                    </div>
                </div>
                <div class="flex-1 relative">
                    <div class="aspect-square rounded-3xl bg-primary/5 overflow-hidden relative border-8 border-white dark:border-slate-800 shadow-2xl">
                        <img class="w-full h-full object-cover"
                            data-alt="Happy baby playing with high-quality wooden toys"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAAkKFfLvzUiApY2H8q_IixrObJ_GPCoXWc8jAYPV-ZFrW-vCuBdzLXonvkXnS8-mM8Hcj8LTER_qHQ2mvBzHQVFqPbaJTuJjDi_Ta3ZVrcD0qw6LLjwBmiRBVCDsJGKi7BsETRkp_qbxSAhq1z1f2F-9IT6Z_srzfL3YzNkmJnM7XAiZJZtxgKaLmiYhFF-k-6iwYBuROX92-aeROkVTE_nebMc4weJJp6uLX-m9VUaPdkLhzYTZx7uVjICBV1SQuqy675K9MnkDUg" />
                    </div>
                    <!-- Floating Badge -->
                    <div class="absolute -bottom-6 -left-6 bg-white dark:bg-slate-800 p-4 rounded-2xl shadow-xl flex items-center gap-4 border border-slate-100 dark:border-slate-700">
                        <div class="bg-primary/20 p-2 rounded-lg text-primary">
                            <span class="material-symbols-outlined">verified_user</span>
                        </div>
                        <div>
                            <p class="text-sm font-bold">Safety Certified</p>
                            <p class="text-xs text-slate-500">Non-toxic Assurance</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Category & Variation Tracking -->
    <section class="bg-white dark:bg-slate-900/50 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-end mb-10 gap-6">
                <div class="max-w-xl">
                    <h2 class="text-3xl font-bold mb-4">Shop by Stage &amp; Need</h2>
                    <p class="text-slate-600 dark:text-slate-400">Every child grows at their own pace. Filter our
                        collection by age, size, and specific needs to find the perfect fit.</p>
                </div>
                <div class="flex gap-2 overflow-x-auto pb-2 w-full md:w-auto">
                    <button class="flex items-center gap-2 bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap">
                        <span class="material-symbols-outlined text-sm">child_care</span>
                        0-6 Months
                    </button>
                    <button class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap">
                        6-12 Months
                    </button>
                    <button class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap">
                        1-3 Years
                    </button>
                    <button class="flex items-center gap-2 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 px-4 py-2 rounded-lg text-sm font-semibold whitespace-nowrap">
                        3+ Years
                    </button>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Product Card 1 -->
                <div class="bg-background-light dark:bg-slate-800 rounded-2xl p-4 transition-transform hover:-translate-y-1">
                    <div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 bg-white">
                        <img class="w-full h-full object-cover"
                            data-alt="Premium ergonomic baby stroller in charcoal grey"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB3B70Z_JS61y-J5daU6c5bCCQbjdI-d7AyXAyWbI2uYRqsjl38PXZF1Ib3B-v9RuXwtdCMN-axQC9LK-JDVA0g1tajeso2iZmUBduyr5F60OtLfbj-b1Q7n1NoJKSjXWMbsdAp74jHYtyJAvNYB5l0Qllrbs04hRb4hwjDq5EToeSp0EboX1CwJvBfvxWKxS2pY3aReGkjfLZVZ3K0v1OasqYbOebrhU0nEyO4XpibIOvcHtbIa0OUDi9LVu0B6WVLuwLutQwwbdJk" />
                    </div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">Travel System Stroller</h3>
                        <span class="text-primary font-bold">$45/wk</span>
                    </div>
                    <div class="flex gap-2 mb-4">
                        <div class="w-5 h-5 rounded-full bg-slate-800 border-2 border-white ring-1 ring-slate-200"></div>
                        <div class="w-5 h-5 rounded-full bg-primary border-2 border-white ring-1 ring-slate-200"></div>
                        <div class="w-5 h-5 rounded-full bg-rose-200 border-2 border-white ring-1 ring-slate-200"></div>
                    </div>
                    <button class="w-full py-2 border border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">Select Options</button>
                </div>
                <!-- Product Card 2 -->
                <div class="bg-background-light dark:bg-slate-800 rounded-2xl p-4 transition-transform hover:-translate-y-1">
                    <div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 bg-white">
                        <img class="w-full h-full object-cover" data-alt="Set of sustainable wooden development toys"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuB9MDMOzfdlEkf9yrMW9-J-4P2cLtUmxrdOerbosgWLeJk_vTUUct4dScu6PZJy7KvpA4bVJVKG8qQdXYtxDNEiwwQdPrl8YUXxBuMr8zSHFLgHS5bSfB77MTJSge7c_gEzLdsAV3uwB0jE8I3RaFRTYmsCSK1d9pzGnQCp7PGb-7b0aZk5s4Vdvmq4JdrZLALvE8aZX_oCHnrflNFM6lKHAlzBXfydjZFZLDxHiR5BptkD-qzL5fF78EFO1BOV8OGKVxcAeyt7bDHh" />
                    </div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">Montessori Toy Kit</h3>
                        <span class="text-primary font-bold">$25/wk</span>
                    </div>
                    <p class="text-xs text-slate-500 mb-4">Age Range: 12-24 Months</p>
                    <button class="w-full py-2 border border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">Select Options</button>
                </div>
                <!-- Product Card 3 -->
                <div class="bg-background-light dark:bg-slate-800 rounded-2xl p-4 transition-transform hover:-translate-y-1">
                    <div class="aspect-[4/3] rounded-xl overflow-hidden mb-4 bg-white">
                        <img class="w-full h-full object-cover" data-alt="Luxury baby high chair with cushioned seat"
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuATtkWVs7RkOBb8XrzPu8oktz_uxnGmEM7KrOwg0yS7Zm_80i6wqK-b14FR0GpvOoYNGDa_Isah6Hbz0rs3SZ5Dqs543D1jUlz6iovp52M9idk3BlSO1LMCZY1mlciQLzKHzcHlnm-7DyQfB_e20Zc3GNj8eQPfl3_KfRMMgZn9iMENNN1BE2q4IQOPiy19vS-HMzzuZ6cQxKCv_2bn9MiUY_ZLU4WB6DE5_wev8Ck5Y6CKQjwtgAF5km_WbBabK0qdg0jnJGGNFbWx" />
                    </div>
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="font-bold text-lg">Luxe Dining High Chair</h3>
                        <span class="text-primary font-bold">$30/wk</span>
                    </div>
                    <div class="flex gap-2 mb-4">
                        <span class="px-2 py-1 bg-slate-200 dark:bg-slate-700 rounded text-[10px] font-bold">SMALL</span>
                        <span class="px-2 py-1 bg-primary/20 text-primary rounded text-[10px] font-bold">STANDARD</span>
                    </div>
                    <button class="w-full py-2 border border-primary text-primary font-bold rounded-lg hover:bg-primary hover:text-white transition-colors">Select Options</button>
                </div>
            </div>
        </div>
    </section>

    <!-- Hygiene & Safety Tracking Section -->
    <section class="py-16 bg-background-light dark:bg-background-dark">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold mb-4">Our Hygiene Protocol</h2>
                <p class="text-slate-600 dark:text-slate-400">We maintain hospital-grade cleaning standards for every
                    item in our inventory.</p>
            </div>
            <div class="space-y-0">
                <div class="grid grid-cols-[48px_1fr] gap-x-6">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">cleaning_services</span>
                        </div>
                        <div class="w-px bg-slate-200 dark:bg-slate-700 grow my-2"></div>
                    </div>
                    <div class="pb-8">
                        <h4 class="text-xl font-bold mb-1">Deep Steam Cleaning</h4>
                        <p class="text-slate-600 dark:text-slate-400">120°C medical-grade steam eliminates 99.9% of
                            bacteria and viruses without harsh chemicals.</p>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-primary">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Standard Protocol
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-[48px_1fr] gap-x-6">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">eco</span>
                        </div>
                        <div class="w-px bg-slate-200 dark:bg-slate-700 grow my-2"></div>
                    </div>
                    <div class="pb-8">
                        <h4 class="text-xl font-bold mb-1">Non-Toxic Sanitization</h4>
                        <p class="text-slate-600 dark:text-slate-400">We use plant-based, eco-certified disinfectants
                            that are safe for oral contact and sensitive skin.</p>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-primary">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Child-Safe Certified
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-[48px_1fr] gap-x-6">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">inventory_2</span>
                        </div>
                    </div>
                    <div>
                        <h4 class="text-xl font-bold mb-1">Vacuum Sealed Packaging</h4>
                        <p class="text-slate-600 dark:text-slate-400">Items are immediately sealed after cleaning to
                            ensure they remain sterile until they reach your home.</p>
                        <div class="mt-2 flex items-center gap-2 text-xs font-semibold text-primary">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            Tamper-Evident Seal
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonial -->
    <section class="py-16 bg-primary/5">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 md:p-12 shadow-xl flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 rounded-bl-full"></div>
                <div class="shrink-0">
                    <img class="w-32 h-32 rounded-2xl object-cover ring-4 ring-primary/20"
                        data-alt="Professional smiling woman, mompreneur review"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuDnFHCfOWiXAe7MPE790YT95iyRL9rlIkwJX4FheVXi154bJzc7SRGPSJ_no3Nn_RmvkVRLrH_0h4yG-hm9n_JL89oW4hC7NFXwVmodfy29Izw2dVzuItO77PKsKpUzSeXH10a-RsVnBRZksE3KUIgWkvv6wAztQjtbxIQjmUmMJnmI--utp2HLIebHY9-BlMxhbqQ_0Owv7o9r1LbReorsA-RKG0Q6SuKNhay1mUzqmJr0bIB4a2KI1MMGaTUBT-CzXEIKTRNTEsUl" />
                </div>
                <div class="space-y-4">
                    <div class="flex gap-1 text-primary">
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                        <span class="material-symbols-outlined">star</span>
                    </div>
                    <p class="text-xl italic font-medium text-slate-700 dark:text-slate-300">
                        "As a working mom, I don't have time to worry about gear safety. The cleaning transparency here
                        is unlike anything I've seen. Every rental comes with a hygiene log that gives me total peace of
                        mind."
                    </p>
                    <div>
                        <h4 class="font-bold text-lg">Sarah Jenkins</h4>
                        <p class="text-primary text-sm font-semibold">Founder, TinySteps Wellness</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 max-w-3xl mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">Frequently Asked Questions</h2>
        <div class="space-y-4">
            <div class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <button class="w-full flex items-center justify-between p-5 text-left font-bold">
                    <span>How do you track the cleaning schedule?</span>
                    <span class="material-symbols-outlined group-hover:rotate-180 transition-transform">expand_more</span>
                </button>
                <div class="px-5 pb-5 text-slate-600 dark:text-slate-400">
                    Every item is assigned a unique QR code. Our team scans and logs each step of the hygiene protocol
                    (Steam, UV, Eco-Sanitize) which you can view digitally by scanning the tag upon arrival.
                </div>
            </div>
            <div class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <button class="w-full flex items-center justify-between p-5 text-left font-bold">
                    <span>What happens if I receive a damaged item?</span>
                    <span class="material-symbols-outlined group-hover:rotate-180 transition-transform">expand_more</span>
                </button>
                <div class="px-5 pb-5 text-slate-600 dark:text-slate-400">
                    We inspect every item before shipping. However, if you notice any issues within the first 24 hours,
                    we offer instant replacement at no extra cost.
                </div>
            </div>
            <div class="group bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl overflow-hidden">
                <button class="w-full flex items-center justify-between p-5 text-left font-bold">
                    <span>Are the toys truly non-toxic?</span>
                    <span class="material-symbols-outlined group-hover:rotate-180 transition-transform">expand_more</span>
                </button>
                <div class="px-5 pb-5 text-slate-600 dark:text-slate-400">
                    Yes. We only source items that meet ASTM and EN71 safety standards. Our cleaning agents are ECOCERT
                    certified plant-based solutions.
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 px-4">
        <div class="max-w-5xl mx-auto bg-slate-900 dark:bg-primary rounded-[2rem] p-8 md:p-16 text-center text-white relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none">
                <div class="absolute top-10 left-10 w-40 h-40 border-4 border-white rounded-full"></div>
                <div class="absolute bottom-10 right-10 w-60 h-60 border-4 border-white rounded-full"></div>
            </div>
            <div class="relative z-10 space-y-8">
                <h2 class="text-4xl md:text-5xl font-black">Ready to make parenting easier?</h2>
                <p class="text-lg text-slate-300 dark:text-white/80 max-w-2xl mx-auto">
                    Join thousands of parents who choose smart, safe, and sustainable rentals. Get 20% off your first
                    rental with code: <span class="bg-white/20 px-2 py-1 rounded font-mono">NEWBORN20</span>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="/register-tenant"
                        class="bg-primary dark:bg-slate-900 text-white px-10 py-5 rounded-2xl font-bold text-lg hover:scale-105 transition-transform">
                        Start Browsing Gear
                    </a>
                    <a href="/contact"
                        class="bg-white text-slate-900 px-10 py-5 rounded-2xl font-bold text-lg hover:scale-105 transition-transform">
                        Contact Safety Expert
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- BEGIN: MainFooter -->
    @include('landing.partials.footer')
    <!-- END: MainFooter -->
</body>

</html>

<?php

namespace Database\Seeders;

use App\Models\LanguageLine;
use Illuminate\Database\Seeder;

class TranslationSeeder extends Seeder
{
    /**
     * Seed translation keys into the language_lines table (central DB).
     * These translations are editable via the Translation Panel in Central Admin.
     *
     * File-based translations in lang/id/ and lang/en/ serve as fallback.
     * Database translations (this seeder) take precedence over file-based.
     */
    public function run(): void
    {
        $this->seedCommon();
        $this->seedLandingHeader();
        $this->seedLandingHero();
        $this->seedLandingFooter();
        $this->seedLandingTestimonials();
        $this->seedLandingFeatures();
        $this->seedLandingCta();
        $this->seedLandingPricing();
        $this->seedLandingPricingPage();
        $this->seedAuth();
        $this->seedPortal();
        $this->seedStorefront();
    }

    protected function seed(string $group, string $key, string $id, string $en): void
    {
        LanguageLine::updateOrCreate(
            ['group' => $group, 'key' => $key],
            ['text' => ['id' => $id, 'en' => $en]]
        );
    }

    protected function seedCommon(): void
    {
        $items = [
            ['login', 'Masuk', 'Login'],
            ['register', 'Daftar', 'Register'],
            ['logout', 'Keluar', 'Logout'],
            ['get_started', 'Mulai Sekarang', 'Get Started'],
            ['home', 'Beranda', 'Home'],
            ['catalog', 'Katalog', 'Catalog'],
            ['search', 'Cari', 'Search'],
            ['loading', 'Memuat...', 'Loading...'],
            ['dashboard', 'Dashboard', 'Dashboard'],
            ['my_rentals', 'Rental Saya', 'My Rentals'],
            ['profile', 'Profil', 'Profile'],
            ['notifications', 'Notifikasi', 'Notifications'],
            ['no_notifications', 'Tidak ada notifikasi', 'No notifications'],
            ['mark_all_read', 'Tandai semua dibaca', 'Mark all read'],
            ['view_all_notifications', 'Lihat semua notifikasi', 'View all notifications'],
            ['notification', 'Notifikasi', 'Notification'],
            ['rental_equipment', 'Peralatan Rental', 'Rental Equipment'],
            ['open_main_menu', 'Buka menu utama', 'Open main menu'],
            ['quick_links', 'Tautan Cepat', 'Quick Links'],
            ['contact', 'Kontak', 'Contact'],
            ['phone', 'Telepon:', 'Phone:'],
            ['email', 'Email:', 'Email:'],
            ['follow_us', 'Ikuti Kami', 'Follow Us'],
            ['default_tagline', 'Mitra rental peralatan terpercaya Anda.', 'Your trusted equipment rental partner.'],
            ['all_rights_reserved', 'Hak cipta dilindungi.', 'All rights reserved.'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('common', $key, $id, $en);
        }
    }

    protected function seedLandingHeader(): void
    {
        $items = [
            ['features', 'Fitur', 'Features'],
            ['live_inventory_stock', 'Stok Inventaris Langsung', 'Live Inventory Stock'],
            ['live_inventory_stock_desc', 'Pelacakan real-time aset Anda di semua lokasi.', 'Real-time tracking of your assets across all locations.'],
            ['advanced_management', 'Manajemen Lanjutan', 'Advanced Management'],
            ['advanced_management_desc', 'Optimalkan level stok dan otomatisasi proses pemesanan ulang.', 'Optimize stock levels and automate reordering processes.'],
            ['booking_online', 'Booking Online', 'Booking Online'],
            ['booking_online_desc', 'Sistem reservasi online yang mulus untuk pelanggan Anda.', 'Seamless online reservation system for your customers.'],
            ['quotation_invoicing', 'Quotation & Invoicing', 'Quotation & Invoicing'],
            ['quotation_invoicing_desc', 'Buat penawaran dan faktur profesional dalam hitungan detik.', 'Generate professional quotes and invoices in seconds.'],
            ['rental_reports', 'Laporan Rental & Keuangan', 'Rental & Financial Reports'],
            ['rental_reports_desc', 'Dapatkan wawasan mendalam tentang kinerja bisnis dan ROI dengan alat pelaporan otomatis.', 'Gain deep insights into your business performance and ROI with automated reporting tools.'],
            ['new_feature', 'Fitur Baru', 'New Feature'],
            ['mobile_management', 'Manajemen Mobile', 'Mobile Management'],
            ['mobile_management_desc', 'Kelola bisnis Anda dari mana saja dengan aplikasi iOS & Android kami.', 'Manage your business from anywhere with our iOS & Android app.'],
            ['explore_all_features', 'Jelajahi semua fitur', 'Explore all features'],
            ['discover_everything', 'Temukan semua yang bisa dilakukan Zewalo', 'Discover everything Zewalo can do'],
            ['solutions', 'Solusi', 'Solutions'],
            ['photography_film', 'Fotografi & Film', 'Photography & Film'],
            ['photography_film_desc', 'Kelola kit kamera, lensa, dan aksesori dengan pelacakan presisi.', 'Manage camera kits, lenses, and accessories with precision tracking.'],
            ['outdoor_camping', 'Outdoor & Camping', 'Outdoor & Camping'],
            ['outdoor_camping_desc', 'Pelacakan real-time untuk operasi perlengkapan camping volume tinggi.', 'Real-time tracking for high-volume camping gear operations.'],
            ['party_event', 'Pesta & Acara', 'Party & Event'],
            ['party_event_desc', 'Cegah double-booking di inventaris acara berskala besar.', 'Prevent double-booking across large-scale event inventories.'],
            ['sound_system', 'Sound System', 'Sound System'],
            ['sound_system_desc', 'Pelacakan berbasis kit untuk mixer, kabel, dan komponen audio.', 'Kit-based tracking for mixers, cables, and audio components.'],
            ['car_motorcycle', 'Mobil & Motor', 'Car & Motorcycle'],
            ['car_motorcycle_desc', 'Perawatan armada, pelacakan dokumen, dan integrasi booking.', 'Fleet maintenance, document tracking, and booking integration.'],
            ['medical_equipment', 'Peralatan Medis', 'Medical Equipment'],
            ['medical_equipment_desc', 'Perawatan otomatis dan pelacakan kepatuhan untuk perangkat medis.', 'Automated maintenance and compliance tracking for medical devices.'],
            ['baby_mom_needs', 'Kebutuhan Bayi & Ibu', 'Baby & Mom Needs'],
            ['baby_mom_needs_desc', 'Manajemen rental mengutamakan kebersihan untuk perlengkapan bayi dan aksesori.', 'Hygiene-first rental management for baby gear and accessories.'],
            ['industry_solutions', 'Solusi Industri', 'Industry Solutions'],
            ['tailored_industry', 'Disesuaikan untuk Industri Anda', 'Tailored for Your Industry'],
            ['tailored_industry_desc', 'Temukan bagaimana Zewalo beradaptasi dengan kebutuhan bisnis rental spesifik Anda.', 'Discover how Zewalo adapts to your specific rental business needs.'],
            ['explore_all_solutions', 'Jelajahi semua solusi', 'Explore all solutions'],
            ['perfect_fit', 'Temukan yang tepat untuk bisnis Anda', 'Find the perfect fit for your business'],
            ['pricing', 'Harga', 'Pricing'],
            ['testimonials', 'Testimoni', 'Testimonials'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.header', $key, $id, $en);
        }
    }

    protected function seedLandingHero(): void
    {
        $items = [
            ['eyebrow', 'Platform Rental Management #1', 'Platform Rental Management #1'],
            ['title_line1', 'Kelola Bisnis Rental', 'Manage Rental Business'],
            ['title_line2', 'Lebih Mudah & Cepat', 'Easier & Faster'],
            ['subtitle', 'Platform all-in-one untuk mengelola penyewaan, inventaris, pelanggan, dan keuangan bisnis rental Anda. Mulai dalam hitungan menit.', 'All-in-one platform to manage rentals, inventory, customers, and finances of your rental business. Get started in minutes.'],
            ['cta_start_trial', 'Mulai Gratis 14 Hari', 'Start Free 14 Days'],
            ['cta_view_demo', 'Lihat Demo', 'View Demo'],
            ['trusted_by', 'Dipercaya :count bisnis rental', 'Trusted by :count rental businesses'],
            ['stat_total_rental', 'Total Rental', 'Total Rental'],
            ['stat_revenue', 'Pendapatan', 'Revenue'],
            ['stat_customers', 'Pelanggan', 'Customers'],
            ['stat_available_units', 'Unit Tersedia', 'Available Units'],
            ['revenue_chart', 'Grafik Pendapatan', 'Revenue Chart'],
            ['last_7_days', '7 hari terakhir', 'Last 7 days'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.hero', $key, $id, $en);
        }
    }

    protected function seedLandingFooter(): void
    {
        $items = [
            ['description', 'Solusi modern untuk manajemen bisnis rental Anda. Efisien, terintegrasi, dan mudah digunakan.', 'Modern solution for your rental business management. Efficient, integrated, and easy to use.'],
            ['company', 'Perusahaan', 'Company'],
            ['about_us', 'Tentang Kami', 'About Us'],
            ['careers', 'Karir', 'Careers'],
            ['blog', 'Blog', 'Blog'],
            ['contact', 'Kontak', 'Contact'],
            ['legal', 'Legal', 'Legal'],
            ['privacy', 'Privasi', 'Privacy'],
            ['terms', 'Ketentuan', 'Terms'],
            ['security', 'Keamanan', 'Security'],
            ['support', 'Dukungan', 'Support'],
            ['help_center', 'Pusat Bantuan', 'Help Center'],
            ['documentation', 'Dokumentasi', 'Documentation'],
            ['system_status', 'Status Sistem', 'System Status'],
            ['copyright', '© :year Zewalo. Seluruh hak cipta dilindungi.', '© :year Zewalo. All rights reserved.'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.footer', $key, $id, $en);
        }
    }

    protected function seedLandingTestimonials(): void
    {
        $items = [
            ['badge', 'Testimoni', 'Testimonials'],
            ['title_prefix', 'Dipercaya oleh', 'Trusted by'],
            ['title_highlight', 'Bisnis Rental', 'Rental Businesses'],
            ['title_suffix', 'di Seluruh Indonesia', 'Across Indonesia'],
            ['subtitle', 'Dengar langsung dari para pemilik usaha rental tentang Zewalo.', 'See what rental business owners say about Zewalo.'],
            ['stat_businesses', 'Bisnis Aktif', 'Active Businesses'],
            ['stat_daily_users', 'Pengguna Harian', 'Daily Users'],
            ['stat_uptime', 'Jaminan Uptime', 'Uptime Guarantee'],
            ['stat_rating', 'Rating Rata-rata', 'Average Rating'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.testimonials', $key, $id, $en);
        }
    }

    protected function seedLandingFeatures(): void
    {
        $items = [
            ['badge', 'Fitur Platform', 'Platform Features'],
            ['title_prefix', 'Semua yang Anda Butuhkan untuk', 'Everything You Need to'],
            ['title_highlight', 'Menjalankan Bisnis Rental Anda', 'Run Your Rental Business'],
            ['subtitle', 'Perangkat lengkap yang dirancang khusus untuk bisnis rental — dari inventaris hingga invoicing, semua dalam satu tempat.', 'A complete toolkit built specifically for rental businesses — from inventory to invoicing, all in one place.'],
            ['inventory_title', 'Manajemen Inventaris', 'Inventory Management'],
            ['inventory_desc', 'Lacak setiap item dan unit secara real-time. Ketahui apa yang tersedia, sedang disewa, atau dalam perawatan sekilas pandang.', 'Track every item and unit in real-time. Know what\'s available, rented out, or under maintenance at a glance.'],
            ['booking_title', 'Pemesanan & Penjadwalan', 'Booking & Scheduling'],
            ['booking_desc', 'Kelola periode sewa, kalender ketersediaan, dan penjadwalan otomatis dengan mudah.', 'Manage rental periods, availability calendars, and automated scheduling with ease.'],
            ['finance_title', 'Keuangan & Invoicing', 'Finance & Invoicing'],
            ['finance_desc', 'Buat invoice, lacak pembayaran, dan kelola akuntansi double-entry — semuanya sudah terintegrasi.', 'Generate invoices, track payments, and manage double-entry accounting — all built in.'],
            ['customer_title', 'Manajemen Pelanggan', 'Customer Management'],
            ['customer_desc', 'Kelola profil pelanggan, riwayat sewa, dan portal pelanggan mandiri untuk akses dokumen dan penyewaan.', 'Maintain customer profiles, rental history, and a self-service customer portal for document and rental access.'],
            ['analytics_title', 'Laporan & Analitik', 'Reports & Analytics'],
            ['analytics_desc', 'Dapatkan wawasan berguna dengan laporan pendapatan, tingkat utilisasi, dan ringkasan pajak — siap kapan pun dibutuhkan.', 'Get actionable insights with revenue reports, utilization rates, and tax summaries — ready when you need them.'],
            ['mobile_title', 'Ramah Mobile', 'Mobile Friendly'],
            ['mobile_desc', 'Akses dasbor Anda dari perangkat apa pun. Kelola bisnis rental Anda dari mana saja, kapan saja.', 'Access your dashboard from any device. Manage your rental business from anywhere, anytime.'],
            ['security_title', 'Aman & Terpercaya', 'Secure & Reliable'],
            ['security_desc', 'Kontrol akses berbasis peran, enkripsi SSL, dan uptime 99,9% agar data Anda selalu aman dan dapat diakses.', 'Role-based access control, SSL encryption, and 99.9% uptime so your data is always safe and accessible.'],
            ['setup_title', 'Pengaturan Cepat', 'Quick Setup'],
            ['setup_desc', 'Aktifkan toko rental Anda dalam hitungan menit. Tidak perlu keahlian teknis — cukup daftar dan mulai.', 'Get your rental store up and running in minutes. No technical expertise required — just sign up and go.'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.features', $key, $id, $en);
        }
    }

    protected function seedLandingCta(): void
    {
        $items = [
            ['title_line1', 'Siap Mengembangkan Bisnis Rental Anda?', 'Ready to Grow Your Rental Business?'],
            ['title_line2', 'Mulai Gratis Sekarang.', 'Start for Free Today.'],
            ['subtitle', 'Bergabung dengan ribuan bisnis rental yang sudah menggunakan Zewalo untuk menyederhanakan operasional dan memanjakan pelanggan mereka.', 'Join thousands of rental businesses already using Zewalo to streamline operations and delight their customers.'],
            ['register_button', 'Mulai Gratis', 'Start for Free'],
            ['demo_button', 'Lihat Demo', 'Watch Demo'],
            ['badge_ssl', 'SSL Terenkripsi', 'SSL Secured'],
            ['badge_uptime', 'Uptime 99,9%', '99.9% Uptime'],
            ['badge_no_cc', 'Tanpa Kartu Kredit', 'No Credit Card Required'],
            ['badge_setup', 'Pengaturan dalam Menit', 'Setup in Minutes'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.cta', $key, $id, $en);
        }
    }

    protected function seedLandingPricing(): void
    {
        $items = [
            ['badge', 'Harga', 'Pricing'],
            ['title_prefix', 'Harga yang Sederhana dan', 'Simple, Transparent'],
            ['title_highlight', 'Transparan', 'Pricing'],
            ['subtitle', 'Mulai gratis, tingkatkan saat butuh lebih. Tanpa biaya tersembunyi.', 'Start free, upgrade when you need more. No hidden fees.'],
            ['monthly', 'Bulanan', 'Monthly'],
            ['yearly', 'Tahunan', 'Yearly'],
            ['save_17', 'Hemat 17%', 'Save 17%'],
            ['per_month', '/bulan', '/month'],
            ['most_popular', 'Paling Populer', 'Most Popular'],
            ['free_plan_name', 'Gratis', 'Free'],
            ['free_plan_desc', 'Sempurna untuk memulai.', 'Perfect for getting started.'],
            ['free_cta', 'Mulai Gratis', 'Get Started Free'],
            ['basic_plan_name', 'Basic', 'Basic'],
            ['basic_plan_desc', 'Untuk bisnis rental yang berkembang.', 'For growing rental businesses.'],
            ['basic_cta', 'Mulai Sekarang', 'Get Started'],
            ['pro_plan_name', 'Pro', 'Pro'],
            ['pro_plan_desc', 'Untuk operasi skala besar atau multi-lokasi.', 'For large-scale or multi-location operations.'],
            ['pro_cta', 'Mulai Sekarang', 'Get Started'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.pricing', $key, $id, $en);
        }
    }

    protected function seedLandingPricingPage(): void
    {
        $items = [
            ['page_title', 'Harga - Zewalo', 'Pricing - Zewalo'],
            ['hero_title_prefix', 'Pilih Paket yang Tepat', 'Choose the Right'],
            ['hero_title_highlight', 'untuk Bisnis Anda', 'Plan for Your Business'],
            ['hero_subtitle', 'Mulai gratis, tingkatkan seiring pertumbuhan. Semua paket termasuk uji coba gratis 14 hari.', 'Start free, scale as you grow. All plans include a 14-day free trial.'],
            ['compare_title', 'Bandingkan Paket', 'Compare Plans'],
            ['compare_subtitle', 'Lihat apa yang ditawarkan setiap paket secara sekilas.', 'See what each plan offers at a glance.'],
            ['cta_title', 'Siap untuk memulai?', 'Ready to get started?'],
            ['cta_subtitle', 'Bergabung dengan ribuan bisnis rental yang sudah menggunakan Zewalo.', 'Join thousands of rental businesses already using Zewalo.'],
            ['cta_button', 'Mulai Uji Coba Gratis', 'Start Free Trial'],
            ['cta_note', 'Tanpa kartu kredit • Bisa dibatalkan kapan saja', 'No credit card required • Cancel anytime'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('landing.pricing_page', $key, $id, $en);
        }
    }

    protected function seedAuth(): void
    {
        $items = [
            ['login', 'Masuk', 'Login'],
            ['sign_in_title', 'Masuk ke akun Anda', 'Sign in to your account'],
            ['or', 'Atau', 'Or'],
            ['create_new_account', 'buat akun baru', 'create a new account'],
            ['email', 'Alamat email', 'Email address'],
            ['password', 'Kata sandi', 'Password'],
            ['remember_me', 'Ingat saya', 'Remember me'],
            ['forgot_password', 'Lupa kata sandi?', 'Forgot password?'],
            ['sign_in', 'Masuk', 'Sign in'],
            ['register', 'Daftar', 'Register'],
            ['select_account_type', 'Pilih Jenis Akun', 'Select Account Type'],
            ['create_account', 'Buat Akun', 'Create Account'],
            ['already_have_account', 'Sudah punya akun?', 'Already have an account?'],
            ['select_your_category', 'Pilih kategori Anda', 'Select your category'],
            ['i_am', 'Saya adalah:', 'I am:'],
            ['go_back', 'Kembali', 'Go Back'],
            ['registering_as', 'Mendaftar sebagai:', 'Registering as:'],
            ['change', 'Ubah', 'Change'],
            ['category', 'Kategori', 'Category'],
            ['full_name', 'Nama Lengkap', 'Full Name'],
            ['phone', 'Nomor Telepon', 'Phone Number'],
            ['confirm_password', 'Konfirmasi Kata Sandi', 'Confirm Password'],
            ['select_option', 'Pilih :label', 'Select :label'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('auth', $key, $id, $en);
        }
    }

    protected function seedPortal(): void
    {
        $items = [
            ['dashboard', 'Dashboard', 'Dashboard'],
            ['welcome', 'Selamat datang, :name!', 'Welcome, :name!'],
            ['active_rentals', 'Rental Aktif', 'Active Rentals'],
            ['completed', 'Selesai', 'Completed'],
            ['cart_items', 'Item Keranjang', 'Cart Items'],
            ['status', 'Status', 'Status'],
            ['view_details', 'Lihat Detail', 'View Details'],
            ['no_active_rentals', 'Tidak ada rental aktif.', 'No active rentals.'],
            ['browse_catalog', 'Jelajahi Katalog', 'Browse Catalog'],
            ['my_rentals', 'Rental Saya', 'My Rentals'],
            ['rental_code', 'Kode Rental', 'Rental Code'],
            ['period', 'Periode', 'Period'],
            ['items', 'Item', 'Items'],
            ['total', 'Total', 'Total'],
            ['to', 'sampai', 'to'],
            ['view', 'Lihat', 'View'],
            ['no_rentals_yet', 'Belum ada rental', 'No rentals yet'],
            ['rental_detail', 'Detail Rental', 'Rental Detail'],
            ['created_on', 'Dibuat pada', 'Created on'],
            ['start_date', 'Tanggal Mulai', 'Start Date'],
            ['end_date', 'Tanggal Selesai', 'End Date'],
            ['deposit', 'Deposit', 'Deposit'],
            ['notes', 'Catatan', 'Notes'],
            ['rental_items', 'Item Rental', 'Rental Items'],
            ['days', 'hari', 'days'],
            ['quantity', 'Jumlah:', 'Quantity:'],
            ['included_kits', 'Kit Termasuk (Total):', 'Included Kits (Total):'],
            ['profile', 'Profil', 'Profile'],
            ['profile_verification', 'Profil & Verifikasi', 'Profile & Verification'],
            ['verification_status', 'Status Verifikasi', 'Verification Status'],
            ['account_status', 'Status Akun', 'Account Status'],
            ['personal_info', 'Informasi Pribadi', 'Personal Info'],
            ['verification_documents', 'Dokumen Verifikasi', 'Verification Documents'],
            ['change_password', 'Ganti Kata Sandi', 'Change Password'],
            ['full_name', 'Nama Lengkap', 'Full Name'],
            ['email', 'Email', 'Email'],
            ['phone_number', 'Nomor Telepon', 'Phone Number'],
            ['address', 'Alamat', 'Address'],
            ['save_changes', 'Simpan Perubahan', 'Save Changes'],
            ['upload_documents', 'Upload Dokumen', 'Upload Documents'],
            ['current_password', 'Kata Sandi Saat Ini', 'Current Password'],
            ['new_password', 'Kata Sandi Baru', 'New Password'],
            ['confirm_new_password', 'Konfirmasi Kata Sandi Baru', 'Confirm New Password'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('portal', $key, $id, $en);
        }
    }

    protected function seedStorefront(): void
    {
        $items = [
            ['checkout.title', 'Checkout', 'Checkout'],
            ['checkout.customer_info', 'Informasi Pelanggan', 'Customer Information'],
            ['checkout.order_items', 'Item Pesanan', 'Order Items'],
            ['checkout.additional_notes', 'Catatan Tambahan', 'Additional Notes'],
            ['checkout.order_summary', 'Ringkasan Pesanan', 'Order Summary'],
            ['checkout.subtotal', 'Subtotal', 'Subtotal'],
            ['checkout.total', 'Total', 'Total'],
            ['checkout.confirm_booking', 'Konfirmasi Booking', 'Confirm Booking'],
            ['checkout.discount_code', 'Kode Diskon', 'Discount Code'],
            ['checkout.enter_code', 'Masukkan kode', 'Enter code'],
            ['checkout.apply', 'Pakai', 'Apply'],
            ['checkout.booking_confirmed', 'Booking Dikonfirmasi', 'Booking Confirmed'],
            ['checkout.booking_submitted', 'Booking Terkirim!', 'Booking Submitted!'],
            ['checkout.view_my_rentals', 'Lihat Rental Saya', 'View My Rentals'],
            ['checkout.continue_browsing', 'Lanjut Belanja', 'Continue Browsing'],
            ['days', 'hari', 'days'],
        ];

        foreach ($items as [$key, $id, $en]) {
            $this->seed('storefront', $key, $id, $en);
        }
    }
}

<?php

return [

    // =============================================
    // NAVIGATION GROUPS
    // =============================================
    'nav' => [
        'inventory' => 'Inventaris',
        'rentals' => 'Penyewaan',
        'sales' => 'Penjualan',
        'system' => 'Sistem',
        'tenant_management' => 'Manajemen Tenant',
        'content_management' => 'Manajemen Konten',
        'admin_roles' => 'Admin & Peran',
    ],

    // =============================================
    // COMMON / SHARED LABELS
    // =============================================
    'common' => [
        'active' => 'Aktif',
        'inactive' => 'Nonaktif',
        'name' => 'Nama',
        'description' => 'Deskripsi',
        'status' => 'Status',
        'type' => 'Tipe',
        'notes' => 'Catatan',
        'amount' => 'Jumlah',
        'date' => 'Tanggal',
        'created' => 'Dibuat',
        'updated' => 'Diperbarui',
        'actions' => 'Aksi',
        'save' => 'Simpan',
        'cancel' => 'Batal',
        'delete' => 'Hapus',
        'edit' => 'Edit',
        'view' => 'Lihat',
        'refresh' => 'Segarkan',
        'print' => 'Cetak',
        'send' => 'Kirim',
        'category' => 'Kategori',
        'code' => 'Kode',
        'auto_generated' => 'Otomatis',
        'currency_prefix' => 'Rp',
        'yes' => 'Ya',
        'no' => 'Tidak',
        'add' => 'Tambah',
        'key' => 'Kunci',
        'value' => 'Nilai',
        'required' => 'Wajib',
        'sort_order' => 'Urutan',
        'settings_saved' => 'Pengaturan berhasil disimpan',
        'account' => 'Akun',
        'payment_date' => 'Tanggal Pembayaran',
        'payment_method' => 'Metode Pembayaran',
        'deposit_to_account' => 'Setorkan ke Akun',
    ],

    // =============================================
    // LANGUAGE SETTINGS
    // =============================================
    'language' => [
        'label' => 'Bahasa',
        'indonesian' => 'Bahasa Indonesia',
        'english' => 'English',
        'switched' => 'Bahasa diubah ke Bahasa Indonesia',
    ],

    // =============================================
    // PAYMENT METHODS
    // =============================================
    'payment_methods' => [
        'cash' => 'Tunai',
        'bank_transfer' => 'Transfer Bank',
        'qris' => 'QRIS',
        'credit_card' => 'Kartu Kredit',
    ],

    // =============================================
    // TENANT ADMIN — PRODUCTS
    // =============================================
    'product' => [
        'nav_label' => 'Katalog Produk',
        'model_label' => 'Katalog Produk',
        'plural_label' => 'Katalog Produk',
    ],

    // =============================================
    // TENANT ADMIN — PRODUCT UNITS
    // =============================================
    'product_unit' => [
        'nav_label' => 'Unit Produk',
        'product' => 'Produk',
        'warehouse' => 'Gudang',
        'purchase_date' => 'Tanggal Pembelian',
        'purchase_price' => 'Harga Beli',
        'residual_value' => 'Nilai Residu',
        'useful_life' => 'Masa Pakai (Bulan)',
        'placeholder_warehouse' => 'Pilih Gudang',
        'placeholder_serial' => 'SN-A7IV-001',
        'helper_residual' => 'Estimasi nilai di akhir masa pakai',
        'suffix_months' => 'bulan',
        'current_value' => 'Nilai Saat Ini',
        'depreciated_value' => 'Nilai Terdepresiasi',
        'profit_loss' => 'Laba/Rugi',
        'profit_loss_desc' => 'Pendapatan - Perawatan - Biaya',
        'filter_category' => 'Kategori',
        'status_available' => 'Tersedia',
        'status_scheduled' => 'Terjadwal',
        'status_rented' => 'Disewa',
        'status_maintenance' => 'Perawatan',
        'status_retired' => 'Pensiun',
        'condition_excellent' => 'Sangat Baik',
        'condition_good' => 'Baik',
        'condition_fair' => 'Cukup',
        'condition_poor' => 'Buruk',
        'condition_broken' => 'Rusak',
        'condition_lost' => 'Hilang',
    ],

    // =============================================
    // TENANT ADMIN — RENTALS
    // =============================================
    'rental' => [
        'nav_label' => 'Penyewaan',
        'badge_quotation' => 'Penawaran',
        'badge_late' => 'Terlambat',
    ],

    // =============================================
    // TENANT ADMIN — CUSTOMERS
    // =============================================
    'customer' => [
        'model_label' => 'Pelanggan',
        'plural_label' => 'Pelanggan',
        'nav_label' => 'Pelanggan',
        'badge_need_verification' => 'perlu verifikasi',
        'email_address' => 'Alamat email',
        'roles' => 'Peran',
        'verified' => 'Terverifikasi',
    ],

    // =============================================
    // TENANT ADMIN — CUSTOMER CATEGORIES
    // =============================================
    'customer_category' => [
        'nav_group' => 'Kategori Pelanggan',
    ],

    // =============================================
    // TENANT ADMIN — INVOICES
    // =============================================
    'invoice' => [
        'section_details' => 'Detail Invoice',
        'record_payment' => 'Catat Pembayaran',
        'add_late_fee' => 'Tambah Denda Keterlambatan',
    ],

    // =============================================
    // TENANT ADMIN — QUOTATIONS
    // =============================================
    'quotation' => [
        'section_details' => 'Detail Penawaran',
        'create_invoice' => 'Buat Invoice dari Penawaran ini',
        'invoice_created' => 'Invoice berhasil dibuat',
        'status_updated' => 'Status diperbarui',
        'sent' => 'Penawaran terkirim',
    ],

    // =============================================
    // TENANT ADMIN — DELIVERIES
    // =============================================
    'delivery' => [
        'nav_label' => 'Pengiriman',
    ],

    // =============================================
    // TENANT ADMIN — DISCOUNTS
    // =============================================
    'discount' => [
        'model_label' => 'Kode Diskon',
        'plural_label' => 'Kode Diskon',
        'section_info' => 'Informasi Diskon',
        'section_limits' => 'Batasan',
        'section_validity' => 'Masa Berlaku',
        'min_rental_amount' => 'Minimum Jumlah Sewa',
        'max_discount_amount' => 'Maksimum Jumlah Diskon',
        'total_usage_limit' => 'Batas Total Penggunaan',
        'per_customer_limit' => 'Batas Per Pelanggan',
        'valid_until' => 'Berlaku Sampai',
    ],

    // =============================================
    // TENANT ADMIN — DAILY DISCOUNTS
    // =============================================
    'daily_discount' => [
        'model_label' => 'Diskon Harian',
        'plural_label' => 'Diskon Harian',
        'section_info' => 'Informasi Diskon Harian',
        'section_validity' => 'Masa Berlaku',
        'section_desc' => 'Contoh: Sewa 3 hari bayar 2 hari',
        'min_days' => 'Minimum Hari Sewa',
        'free_days' => 'Hari Gratis',
        'max_discount' => 'Maksimum Diskon',
        'priority' => 'Prioritas',
        'start_date' => 'Tanggal Mulai',
        'end_date' => 'Tanggal Berakhir',
        'placeholder_name' => 'Sewa 3 Bayar 2',
        'placeholder_desc' => 'Deskripsi promosi...',
        'helper_min_days' => 'Jumlah hari minimum untuk mendapat diskon',
        'helper_free_days' => 'Jumlah hari yang digratiskan',
        'helper_no_limit' => 'Kosongkan jika tidak ada batas',
        'helper_priority' => 'Prioritas lebih tinggi diutamakan',
        'col_min_days' => 'Min. Hari',
        'col_free_days' => 'Hari Gratis',
        'col_max_discount' => 'Maks. Diskon',
        'col_valid_until' => 'Berlaku Sampai',
    ],

    // =============================================
    // TENANT ADMIN — DATE PROMOTIONS
    // =============================================
    'date_promotion' => [
        'model_label' => 'Promosi Tanggal',
        'plural_label' => 'Promosi Tanggal',
        'section_info' => 'Informasi Promosi Tanggal',
        'section_dates' => 'Tanggal Promo',
        'section_desc' => 'Promo pada tanggal tertentu (misal: Hari Kemerdekaan, Natal, dll)',
        'discount_type' => 'Tipe Diskon',
        'discount_value' => 'Nilai Diskon',
        'max_discount' => 'Maksimum Diskon',
        'priority' => 'Prioritas',
        'promo_dates' => 'Tanggal Promo',
        'yearly_recurring' => 'Berulang Setiap Tahun',
        'placeholder_name' => 'Promo Hari Kemerdekaan',
        'placeholder_desc' => 'Deskripsi promosi...',
        'helper_value' => 'Persentase atau nominal',
        'helper_no_limit' => 'Kosongkan jika tidak ada batas',
        'helper_priority' => 'Prioritas lebih tinggi diutamakan',
        'helper_dates' => 'Tanggal spesifik untuk promo',
        'helper_yearly' => 'Aktif pada tanggal yang sama setiap tahun',
        'col_promo_dates' => 'Tanggal Promo',
        'col_type' => 'Tipe',
        'col_value' => 'Nilai',
        'col_yearly' => 'Tahunan',
    ],

    // =============================================
    // TENANT ADMIN — WAREHOUSES
    // =============================================
    'warehouse' => [
        'nav_label' => 'Gudang',
        'available_for_rental' => 'Tersedia untuk Sewa',
        'rental_available' => 'Sewa Tersedia',
        'helper_rental' => 'Jika dinonaktifkan, unit di gudang ini tidak bisa disewakan.',
    ],

    // =============================================
    // TENANT ADMIN — MAINTENANCE
    // =============================================
    'maintenance' => [
        'label' => 'Perawatan',
        'plural_label' => 'Perawatan & QC',
        'col_product' => 'Produk',
        'col_status' => 'Status',
        'col_progress' => 'Progres Perawatan',
        'col_last_qc' => 'QC Terakhir',
        'status_unit_lost' => 'Unit Hilang',
        'status_unit_broken' => 'Unit Rusak',
        'status_kit_lost' => 'Kit Hilang',
        'status_kit_broken' => 'Kit Rusak',
        'filter_needs_attention' => 'Perlu Perhatian (Rusak/Hilang/Perawatan/Kit)',
        'action_manage' => 'Kelola',
        'action_qc_passed' => 'QC Lulus',
        'action_record_cost' => 'Catat Biaya',
        'action_update_progress' => 'Perbarui Progres',
        'action_resolve' => 'Selesaikan Masalah',
        'modal_manage' => 'Kelola Unit & Kit',
        'unit_condition' => 'Kondisi Unit',
        'unit_maintenance_status' => 'Status Perawatan Unit',
        'unit_notes' => 'Catatan Unit',
        'maintenance_status' => 'Status Perawatan',
        'action_taken' => 'Tindakan yang Dilakukan',
        'final_unit_condition' => 'Kondisi Akhir Unit',
        'kit_final_conditions' => 'Kondisi Akhir Kit',
        'resolution_notes' => 'Catatan Penyelesaian',
        'expense_title' => 'Judul Pengeluaran',
        'source_account' => 'Akun Sumber',
        'maintenance_notes' => 'Catatan Perawatan',
        'status_in_repair' => 'Sedang Diperbaiki',
        'status_waiting_parts' => 'Menunggu Suku Cadang',
        'status_ready_qc' => 'Siap QC',
        'status_waiting_customer' => 'Menunggu Pelanggan',
        'resolution_repaired' => 'Diperbaiki (Servis)',
        'resolution_replaced' => 'Diganti (Unit Baru)',
        'resolution_found' => 'Ditemukan (Sempat Hilang)',
        'resolution_write_off' => 'Dihapuskan (Pensiun)',
        'placeholder_expense' => 'cth. Penggantian Suku Cadang',
        'notif_stock_opname' => 'Stok Opname Tercatat',
        'notif_expense' => 'Pengeluaran Tercatat',
        'notif_resolved' => 'Masalah Terselesaikan',
        'notif_updated' => 'Unit dan kit berhasil diperbarui.',
    ],

    // =============================================
    // TENANT ADMIN — DOCUMENT TYPES
    // =============================================
    'document_type' => [
        'nav_label' => 'Tipe Dokumen',
        'nav_group' => 'Pengaturan',
        'required_verification' => 'Wajib untuk verifikasi',
    ],

    // =============================================
    // TENANT ADMIN — EMAIL LOGS
    // =============================================
    'email_log' => [
        'nav_label' => 'Log Email',
        'model_label' => 'Log Email',
        'plural_label' => 'Log Email',
        'recipient' => 'Penerima',
        'error_message' => 'Pesan Error',
        'sent_at' => 'Terkirim Pada',
        'created_at' => 'Dibuat Pada',
        'error' => 'Error',
        'triggered_by' => 'Dipicu Oleh',
        'filter_sent' => 'Terkirim',
        'filter_failed' => 'Gagal',
    ],

    // =============================================
    // TENANT ADMIN — NAVIGATION
    // =============================================
    'navigation' => [
        'location' => 'Lokasi',
        'header' => 'Header',
        'footer' => 'Footer',
        'set_header' => 'Set Header',
        'set_footer' => 'Set Footer',
    ],

    // =============================================
    // TENANT ADMIN — ROLES
    // =============================================
    'role' => [
        'global' => 'Global',
    ],

    // =============================================
    // TENANT ADMIN — ADMINS & STAFF
    // =============================================
    'staff' => [
        'nav_label' => 'Admin & Staf',
    ],

    // =============================================
    // TENANT ADMIN — USERS
    // =============================================
    'user' => [
        'tab_details' => 'Detail Pengguna',
        'tab_customer' => 'Informasi Pelanggan',
        'tab_additional' => 'Informasi Tambahan',
        'tab_tax' => 'Identitas Pajak',
        'tab_account' => 'Akun',
        'nik_ktp' => 'NIK / KTP',
        'tax_name' => 'Nama Pajak (Nama Faktur Pajak)',
        'npwp' => 'NPWP',
        'tax_reg_number' => 'Nomor Registrasi Pajak (TRN/VAT ID)',
        'tax_entity_type' => 'Tipe Entitas Pajak',
        'pkp' => 'PKP (Pengusaha Kena Pajak)',
        'tax_exempt' => 'Bebas Pajak (Zero-Rated)',
        'tax_address' => 'Alamat Pajak',
        'tax_country' => 'Negara Pajak',
        'verified_customer' => 'Pelanggan Terverifikasi',
        'reset_password' => 'Reset Password',
        'placeholder_tax_name' => 'Sesuai NPWP/KTP',
        'placeholder_international' => 'Untuk pelanggan internasional',
        'helper_pkp' => 'Aktifkan jika pelanggan ini adalah PKP.',
        'helper_tax_exempt' => 'Aktifkan untuk instansi pemerintah atau jasa ekspor (Tanpa Pajak).',
        'helper_reset_password' => 'Reset Password menjadi "resetpassword"',
        'entity_personal' => 'Pribadi',
        'entity_corporate' => 'Badan Usaha',
        'entity_government' => 'Instansi Pemerintah',
        'modal_reset_confirm' => 'Apakah Anda yakin ingin mereset password pengguna ini menjadi "resetpassword"?',
        'modal_reset_heading' => 'Reset Password',
        'country_id' => 'Indonesia',
        'country_sg' => 'Singapura',
        'country_my' => 'Malaysia',
        'country_us' => 'Amerika Serikat',
        'country_uk' => 'Britania Raya',
        'country_au' => 'Australia',
        'country_jp' => 'Jepang',
        'country_cn' => 'Tiongkok',
        'country_in' => 'India',
        'country_th' => 'Thailand',
        'country_vn' => 'Vietnam',
        'country_ph' => 'Filipina',
    ],

    // =============================================
    // CENTRAL ADMIN — TENANTS
    // =============================================
    'tenant' => [
        'tab_tenant' => 'Tenant',
        'tab_business' => 'Informasi Bisnis',
        'tab_owner' => 'Profil Pemilik',
        'tab_subscription' => 'Langganan & Status',
        'section_identity' => 'Identitas Tenant',
        'section_domains' => 'Domain',
        'section_admin_user' => 'User Admin Sistem',
        'section_subscription' => 'Langganan',
        'status_trial' => 'Uji Coba',
        'status_active' => 'Aktif',
        'status_inactive' => 'Nonaktif',
        'status_suspended' => 'Ditangguhkan',
        'currency_idr' => 'Indonesia (IDR)',
        'currency_usd' => 'Internasional (USD)',
        'trial_ends' => 'Uji Coba Berakhir',
        'subscription_ends' => 'Langganan Berakhir',
        'feature_overrides' => 'Override Fitur',
        'additional_data' => 'Data Tambahan',
        'custom_data' => 'Data Kustom',
        'add_domain' => 'Tambah Domain',
        'add_data' => 'Tambah Data',
        'col_id' => 'ID',
        'col_company' => 'Perusahaan',
        'col_email' => 'Email',
        'col_plan' => 'Paket',
        'col_expires' => 'Kedaluwarsa',
        'action_access' => 'Akses Tenant',
        'action_suspend' => 'Tangguhkan',
        'action_activate' => 'Aktifkan',
        'action_suspend_selected' => 'Tangguhkan Terpilih',
        'filter_indonesia' => 'Indonesia',
        'filter_international' => 'Internasional',
    ],

    // =============================================
    // CENTRAL ADMIN — TENANT CATEGORIES
    // =============================================
    'tenant_category' => [
        'nav_label' => 'Kategori Tenant',
        'section_info' => 'Informasi Kategori',
    ],

    // =============================================
    // CENTRAL ADMIN — ADMIN USERS
    // =============================================
    'admin_user' => [
        'nav_label' => 'Pengguna Admin',
        'section_info' => 'Informasi Pengguna',
    ],

    // =============================================
    // CENTRAL ADMIN — SUBSCRIPTION PLANS
    // =============================================
    'subscription_plan' => [
        'section_details' => 'Detail Paket',
        'section_pricing' => 'Harga',
        'section_multi_currency' => 'Harga Multi-Mata Uang',
        'section_limits' => 'Batasan',
        'section_features' => 'Fitur',
        'section_settings' => 'Pengaturan',
        'monthly_price' => 'Harga Bulanan',
        'yearly_price' => 'Harga Tahunan',
        'idr' => 'IDR - Rupiah Indonesia',
        'usd' => 'USD - Dolar AS',
        'eur' => 'EUR - Euro',
        'monthly' => 'Bulanan',
        'yearly' => 'Tahunan',
        'gateway' => 'Gateway',
        'gateway_duitku' => 'Duitku (IDR)',
        'gateway_lemon' => 'LemonSqueezy (USD)',
        'gateway_auto' => 'Otomatis',
        'max_users' => 'Maks Pengguna',
        'max_products' => 'Maks Produk',
        'max_storage' => 'Maks Penyimpanan (MB)',
        'max_domains' => 'Maks Domain',
        'max_rentals' => 'Maks Transaksi Sewa / Bulan',
        'featured' => 'Unggulan',
        'col_users' => 'Pengguna',
        'col_products' => 'Produk',
        'col_storage' => 'Penyimpanan',
        'col_rental_month' => 'Sewa / Bulan',
        'col_tenants' => 'Tenant',
        'col_order' => 'Urutan',
    ],

    // =============================================
    // CENTRAL ADMIN — SAAS INVOICES
    // =============================================
    'saas_invoice' => [
        'nav_label' => 'Tagihan & Invoice',
        'section_details' => 'Detail Invoice',
        'section_amounts' => 'Jumlah',
        'section_dates' => 'Tanggal',
        'section_payment' => 'Pembayaran',
        'tenant' => 'Tenant',
        'invoice_number' => 'Nomor Invoice',
        'subscription' => 'Langganan',
        'status_pending' => 'Menunggu',
        'status_paid' => 'Lunas',
        'status_overdue' => 'Jatuh Tempo',
        'status_cancelled' => 'Dibatalkan',
        'currency_idr' => 'IDR',
        'currency_usd' => 'USD',
        'currency_eur' => 'EUR',
        'col_issued' => 'Diterbitkan',
        'col_due' => 'Jatuh Tempo',
        'col_via' => 'Via',
        'action_mark_paid' => 'Tandai Lunas',
        'confirm_mark_paid' => 'Tandai Invoice sebagai Lunas?',
        'confirm_mark_paid_desc' => 'Tindakan ini akan menandai invoice sebagai lunas dan mengaktifkan subscription tenant.',
    ],

    // =============================================
    // CENTRAL ADMIN — PAYMENT GATEWAYS
    // =============================================
    'payment_gateway' => [
        'nav_label' => 'Payment Gateway',
        'section_identity' => 'Identitas Gateway',
        'section_credentials' => 'Kredensial',
        'section_callbacks' => 'URL Callback',
        'section_settings' => 'Pengaturan',
        'merchant_code' => 'Kode Merchant',
        'api_key' => 'API Key',
        'callback_url' => 'URL Callback (Notifikasi)',
        'return_url' => 'URL Kembali (Redirect setelah bayar)',
        'sandbox_mode' => 'Mode Sandbox',
        'col_methods' => 'Metode',
    ],

    // =============================================
    // CENTRAL ADMIN — PAYMENT METHODS (CENTRAL)
    // =============================================
    'payment_method_central' => [
        'nav_label' => 'Metode Pembayaran',
        'section_config' => 'Konfigurasi Metode',
        'section_fee' => 'Biaya & Pengaturan',
        'fee_type' => 'Tipe Biaya',
        'fee_fixed' => 'Tetap (Rp)',
        'fee_percentage' => 'Persentase (%)',
    ],

    // =============================================
    // CENTRAL ADMIN — TRANSLATIONS
    // =============================================
    'translation' => [
        'nav_label' => 'Terjemahan',
        'section_key' => 'Kunci Terjemahan',
        'section_translations' => 'Terjemahan',
        'lang_id' => 'Indonesia (ID)',
        'lang_en' => 'Inggris (EN)',
        'col_id' => 'ID',
        'col_en' => 'EN',
    ],

    // =============================================
    // CENTRAL ADMIN — BRANDING SETTINGS
    // =============================================
    'branding' => [
        'nav_label' => 'Branding & SEO',
        'section_identity' => 'Identitas Brand',
        'section_seo' => 'SEO & Meta Tags',
        'site_name' => 'Nama Website',
        'logo' => 'Logo',
        'favicon' => 'Favicon',
        'site_description' => 'Deskripsi Website',
        'meta_keywords' => 'Meta Keywords',
        'og_image' => 'Open Graph Image',
        'notif_saved' => 'Pengaturan branding berhasil disimpan',
        'notif_failed' => 'Gagal menyimpan pengaturan branding',
    ],

    // =============================================
    // CENTRAL ADMIN — EMAIL SETTINGS
    // =============================================
    'email_settings' => [
        'nav_label' => 'Pengaturan Email',
        'section_method' => 'Metode Pengiriman Email',
        'section_smtp' => 'Konfigurasi SMTP',
        'section_ses' => 'Konfigurasi Amazon SES',
        'section_sender' => 'Identitas Pengirim',
        'section_test' => 'Uji Koneksi',
        'mailer_smtp' => 'SMTP',
        'mailer_sesv2' => 'Amazon SES v2 (Direkomendasikan)',
        'mailer_ses' => 'Amazon SES v1',
        'mailer_mailgun' => 'Mailgun',
        'mailer_postmark' => 'Postmark',
        'mailer_log' => 'Log (Untuk Testing)',
        'mailer_driver' => 'Mailer / Driver',
        'host' => 'Host',
        'port' => 'Port',
        'username' => 'Username',
        'password' => 'Password',
        'encryption' => 'Enkripsi',
        'encryption_tls' => 'TLS',
        'encryption_ssl' => 'SSL',
        'encryption_none' => 'Tidak Ada',
        'aws_access_key' => 'AWS Access Key ID',
        'aws_secret_key' => 'AWS Secret Access Key',
        'aws_region' => 'AWS Region',
        'region_singapore' => 'Asia Pasifik — Singapura (ap-southeast-1)',
        'region_jakarta' => 'Asia Pasifik — Jakarta (ap-southeast-3)',
        'region_tokyo' => 'Asia Pasifik — Tokyo (ap-northeast-1)',
        'region_virginia' => 'AS Timur — N. Virginia (us-east-1)',
        'region_oregon' => 'AS Barat — Oregon (us-west-2)',
        'region_ireland' => 'Eropa — Irlandia (eu-west-1)',
        'region_frankfurt' => 'Eropa — Frankfurt (eu-central-1)',
        'from_address' => 'Alamat Pengirim',
        'from_name' => 'Nama Pengirim',
        'send_test' => 'Kirim Email Test',
        'notif_saved' => 'Pengaturan email berhasil disimpan',
        'notif_failed' => 'Gagal menyimpan pengaturan email',
    ],

    // =============================================
    // CENTRAL ADMIN — R2 STORAGE SETTINGS
    // =============================================
    'r2_storage' => [
        'nav_label' => 'R2 Storage',
        'title' => 'Pengaturan Cloudflare R2 Storage',
        'section_credentials' => 'Kredensial API',
        'section_bucket' => 'Konfigurasi Bucket',
        'access_key' => 'Access Key ID',
        'secret_key' => 'Secret Access Key',
        'bucket_name' => 'Nama Bucket',
        'endpoint_url' => 'URL Endpoint R2',
        'public_url' => 'URL Publik (Opsional)',
        'region' => 'Region',
        'path_style' => 'Gunakan Path Style Endpoint',
        'save_config' => 'Simpan Konfigurasi',
        'test_connection' => 'Test Koneksi',
        'notif_saved' => 'Konfigurasi R2 berhasil disimpan!',
        'notif_failed' => 'Gagal menyimpan konfigurasi',
        'notif_connected' => 'Koneksi Berhasil!',
        'notif_connect_failed' => 'Koneksi Gagal',
        'notif_stats_updated' => 'Statistik diperbarui',
    ],

    // =============================================
    // CENTRAL ADMIN — SERVER SETTINGS
    // =============================================
    'server' => [
        'section_app' => 'Pengaturan Aplikasi',
        'section_db_cache' => 'Database & Cache',
        'app_name' => 'Nama Aplikasi',
        'environment' => 'Environment',
        'debug_mode' => 'Mode Debug',
        'app_url' => 'URL Aplikasi',
        'db_driver' => 'Driver Database',
        'cache_driver' => 'Driver Cache',
        'session_driver' => 'Driver Session',
        'queue_driver' => 'Driver Queue',
        'php_version' => 'Versi PHP',
        'laravel_version' => 'Versi Laravel',
        'storage_permissions' => 'Izin Storage',
        'cache' => 'Cache',
        'storage_symlink' => 'Symlink Storage',
        'failed_jobs' => 'Job Gagal',
        'clear_cache' => 'Bersihkan Cache',
        'deep_clean' => 'Pembersihan Mendalam',
        'optimize' => 'Optimasi',
        'fix_storage_link' => 'Perbaiki Link Storage',
        'retry_failed_jobs' => 'Ulangi Job Gagal',
        'notif_cache_cleared' => 'Cache Dibersihkan',
        'notif_cache_cleared_desc' => 'Semua cache berhasil dibersihkan.',
        'notif_deep_clean' => 'Pembersihan Cache Mendalam Selesai',
        'notif_optimized' => 'Aplikasi Dioptimasi',
        'notif_optimized_desc' => 'Aplikasi telah dioptimasi untuk produksi.',
        'notif_storage_fixed' => 'Link Storage Diperbaiki',
        'notif_storage_fixed_desc' => 'Symlink storage berhasil dibuat.',
        'notif_jobs_retried' => 'Job Gagal Diulangi',
        'notif_jobs_retried_desc' => 'Semua job gagal telah diantrekan untuk diulangi.',
    ],

    // =============================================
    // CENTRAL ADMIN — DATABASE MANAGEMENT
    // =============================================
    'database' => [
        'migrate_central' => 'Migrasi Central',
        'migrate_tenants' => 'Migrasi Semua Tenant',
        'notif_central_complete' => 'Migrasi Central Selesai',
        'notif_tenant_complete' => 'Migrasi Tenant Selesai',
        'notif_tenant_complete_desc' => 'Semua database tenant telah dimigrasi.',
        'notif_refreshed' => 'Disegarkan',
    ],

    // =============================================
    // CENTRAL ADMIN — R2 FILE BROWSER
    // =============================================
    'file_browser' => [
        'nav_label' => 'Penjelajah File',
        'title' => 'Penjelajah File R2',
        'notif_deleted' => 'Item berhasil dihapus',
        'notif_delete_failed' => 'Gagal menghapus item',
        'notif_refreshed' => 'Data diperbarui',
    ],

    // =============================================
    // CENTRAL ADMIN — STORAGE MANAGEMENT
    // =============================================
    'storage' => [
        'total_space' => 'Total Ruang',
        'free_space' => 'Ruang Kosong',
        'used_space' => 'Ruang Terpakai',
        'usage_percentage' => 'Persentase Penggunaan',
        'logs_size' => 'Ukuran Log',
        'cache_size' => 'Ukuran Cache',
        'sessions_size' => 'Ukuran Session',
        'views_cache_size' => 'Ukuran Cache View',
        'clear_logs' => 'Bersihkan Log',
        'clear_sessions' => 'Bersihkan Session',
        'notif_logs_cleared' => 'Log Dibersihkan',
        'notif_logs_cleared_desc' => 'Semua file log telah dihapus.',
        'notif_sessions_cleared' => 'Session Dibersihkan',
        'notif_sessions_cleared_desc' => 'Semua file session telah dihapus.',
        'notif_refreshed' => 'Disegarkan',
        'notif_refreshed_desc' => 'Informasi penyimpanan telah disegarkan.',
    ],

    // =============================================
    // FINANCE CLUSTER — FINANCE ACCOUNTS
    // =============================================
    'finance_account' => [
        'nav_label' => 'Kas & Bank',
        'model_label' => 'Kas & Bank',
    ],

    // =============================================
    // FINANCE CLUSTER — FINANCE TRANSACTIONS
    // =============================================
    'finance_transaction' => [
        'nav_label' => 'Entri Jurnal',
    ],

    // =============================================
    // FINANCE CLUSTER — JOURNAL ENTRIES
    // =============================================
    'journal_entry' => [
        'nav_label' => 'Entri Jurnal',
        'model_label' => 'Entri Jurnal',
        'plural_label' => 'Entri Jurnal',
        'section_details' => 'Detail Entri',
        'section_items' => 'Item Jurnal',
        'current_balance' => 'Saldo Saat Ini',
        'add_item' => 'Tambah Item',
        'filter_account' => 'Filter berdasarkan Akun',
    ],

    // =============================================
    // FINANCE CLUSTER — ACCOUNT MAPPINGS
    // =============================================
    'account_mapping' => [
        'nav_label' => 'Pemetaan Jurnal',
        'model_label' => 'Pemetaan',
        'plural_label' => 'Pemetaan Jurnal',
        'event_invoice_receivable' => 'Invoice Dibuat (Piutang)',
        'event_invoice_revenue' => 'Invoice Dibuat (Pendapatan)',
        'event_invoice_tax' => 'Invoice Dibuat (Pajak)',
        'event_receive_payment' => 'Terima Pembayaran (Kas/Bank)',
        'event_deposit_received' => 'Deposit Keamanan Diterima (Kas)',
        'event_deposit_refunded' => 'Deposit Keamanan Dikembalikan',
        'event_expense' => 'Pengeluaran Dicatat',
        'side_debit' => 'Debit',
        'side_credit' => 'Kredit',
        'col_code' => 'Kode',
        'col_account_name' => 'Nama Akun',
    ],

    // =============================================
    // FINANCE CLUSTER — CHART OF ACCOUNTS
    // =============================================
    'chart_of_accounts' => [
        'nav_label' => 'Bagan Akun',
        'model_label' => 'Akun',
        'plural_label' => 'Bagan Akun',
        'account_code' => 'Kode Akun',
        'account_name' => 'Nama Akun',
        'parent_account' => 'Akun Induk',
        'sub_type' => 'Sub Tipe',
        'is_sub_account' => 'Adalah Sub Akun',
        'type_asset' => 'Aset (Harta)',
        'type_liability' => 'Liabilitas (Kewajiban)',
        'type_equity' => 'Ekuitas (Modal)',
        'type_revenue' => 'Pendapatan',
        'type_expense' => 'Beban',
        'col_parent' => 'Induk',
        'col_sub' => 'Sub',
    ],

    // =============================================
    // FINANCE CLUSTER — BILLS (ACCOUNTS PAYABLE)
    // =============================================
    'bill' => [
        'nav_label' => 'Hutang Usaha',
        'section_details' => 'Detail Tagihan',
        'bill_number' => 'Nomor Tagihan / Invoice',
        'category_utilities' => 'Utilitas',
        'category_inventory' => 'Inventaris',
        'category_service' => 'Jasa',
        'category_rent' => 'Sewa',
        'category_other' => 'Lainnya',
        'col_due' => 'Jatuh Tempo',
        'status_pending' => 'Menunggu',
        'status_partial' => 'Sebagian',
        'status_paid' => 'Lunas',
        'status_overdue' => 'Jatuh Tempo',
        'action_pay' => 'Bayar',
        'pay_from_account' => 'Bayar dari Akun',
        'payment_amount' => 'Jumlah Pembayaran',
        'notif_payment_recorded' => 'Pembayaran Tercatat',
    ],

    // =============================================
    // FINANCE CLUSTER — EXPENSES
    // =============================================
    'expense' => [
        'nav_label' => 'Pengeluaran Operasional',
        'section_details' => 'Detail Pengeluaran',
        'paid_from_account' => 'Dibayar dari Akun',
        'category_operational' => 'Operasional',
        'category_utilities' => 'Utilitas',
        'category_salary' => 'Gaji',
        'category_maintenance' => 'Perawatan',
        'category_fuel' => 'BBM',
        'category_marketing' => 'Pemasaran',
        'category_other' => 'Lainnya',
        'col_account' => 'Akun',
        'col_recorded_by' => 'Dicatat Oleh',
    ],

    // =============================================
    // FINANCE CLUSTER — ACCOUNTS RECEIVABLE PAGE
    // =============================================
    'accounts_receivable' => [
        'nav_label' => 'Piutang Usaha',
        'col_customer' => 'Pelanggan',
        'col_due' => 'Jatuh Tempo',
        'action_record_payment' => 'Catat Pembayaran',
        'notif_payment_recorded' => 'Pembayaran Tercatat',
    ],

    // =============================================
    // FINANCE CLUSTER — CUSTOMER DEPOSITS PAGE
    // =============================================
    'customer_deposit' => [
        'nav_label' => 'Deposit Pelanggan',
        'title' => 'Kontrol Deposit Pelanggan',
        'col_customer' => 'Pelanggan',
        'col_required' => 'Deposit Wajib',
        'col_held' => 'Jumlah Ditahan',
        'action_receive' => 'Terima',
        'action_refund' => 'Kembalikan',
        'amount_received' => 'Jumlah Diterima',
        'refund_amount' => 'Jumlah Pengembalian',
        'deduction' => 'Potongan (Kerusakan/Keterlambatan)',
        'refund_notes' => 'Catatan Pengembalian',
        'status_pending' => 'Menunggu',
        'status_held' => 'Ditahan',
        'status_refunded' => 'Dikembalikan',
        'status_forfeited' => 'Disita',
        'notif_received' => 'Deposit Diterima',
        'notif_refunded' => 'Deposit Dikembalikan',
    ],

    // =============================================
    // FINANCE CLUSTER — FINANCIAL REPORTS PAGE
    // =============================================
    'financial_reports' => [
        'nav_label' => 'Laporan',
    ],

    // =============================================
    // SETTINGS CLUSTER — GENERAL SETTINGS
    // =============================================
    'general_settings' => [
        'nav_label' => 'Informasi Bisnis',
        'logo' => 'Logo',
        'site_name' => 'Nama Situs',
        'site_description' => 'Deskripsi Situs',
        'company_name' => 'Nama Perusahaan',
        'address' => 'Alamat',
        'phone' => 'Telepon',
        'email' => 'Email',
    ],

    // =============================================
    // SETTINGS CLUSTER — APPEARANCE SETTINGS
    // =============================================
    'appearance' => [
        'nav_label' => 'Tampilan',
        'theme_preset' => 'Preset Tema',
        'custom_color' => 'Warna Kustom',
        'nav_layout' => 'Tata Letak Navigasi',
        'layout_sidebar' => 'Sidebar',
        'layout_top' => 'Navigasi Atas',
        'preset_default' => 'Default',
        'preset_slate' => 'Slate',
        'preset_gray' => 'Gray',
        'preset_zinc' => 'Zinc',
        'preset_neutral' => 'Neutral',
        'preset_stone' => 'Stone',
        'preset_red' => 'Merah',
        'preset_orange' => 'Oranye',
        'preset_amber' => 'Amber',
        'preset_yellow' => 'Kuning',
        'preset_lime' => 'Lime',
        'preset_green' => 'Hijau',
        'preset_emerald' => 'Emerald',
        'preset_teal' => 'Teal',
        'preset_cyan' => 'Cyan',
        'preset_sky' => 'Sky',
        'preset_blue' => 'Biru',
        'preset_indigo' => 'Indigo',
        'preset_violet' => 'Violet',
        'preset_purple' => 'Ungu',
        'preset_fuchsia' => 'Fuchsia',
        'preset_pink' => 'Pink',
        'preset_rose' => 'Rose',
        'preset_custom' => 'Kustom',
    ],

    // =============================================
    // SETTINGS CLUSTER — DOCUMENT LAYOUT
    // =============================================
    'doc_layout' => [
        'nav_label' => 'Tata Letak Dokumen',
        'tab_invoice' => 'Invoice',
        'tab_quotation' => 'Penawaran',
        'tab_delivery' => 'Surat Jalan',
        'tab_checklist' => 'Formulir Checklist',
        'tab_branding' => 'Branding & Gaya',
        'tab_company' => 'Informasi Perusahaan',
        'tab_content' => 'Konten Dokumen',
        'tab_qr' => 'Kode QR',
        'section_visual' => 'Identitas Visual',
        'section_colors' => 'Warna',
        'section_table' => 'Opsi Tabel',
        'section_company' => 'Informasi Perusahaan',
        'section_qr' => 'Visibilitas Kode QR',
        'doc_logo' => 'Logo Dokumen',
        'show_logo' => 'Tampilkan Logo di Dokumen',
        'font_family' => 'Jenis Font',
        'font_dejavu' => 'DejaVu Sans (Default)',
        'font_helvetica' => 'Helvetica',
        'font_arial' => 'Arial',
        'font_times' => 'Times New Roman',
        'font_courier' => 'Courier',
        'primary_color' => 'Warna Utama',
        'secondary_color' => 'Warna Sekunder',
        'striped_rows' => 'Baris Bergaris',
        'bordered_table' => 'Tabel Berbingkai',
        'company_name' => 'Nama Perusahaan',
        'phone' => 'Telepon',
        'email' => 'Email',
        'website' => 'Website',
        'tax_id' => 'NPWP',
        'address' => 'Alamat',
        'custom_header' => 'Teks Header Kustom',
        'custom_footer' => 'Teks Footer Kustom',
        'quotation_terms' => 'Syarat & Ketentuan Penawaran',
        'bank_details' => 'Detail Rekening Bank',
        'show_qr_delivery' => 'Tampilkan Kode QR di Surat Jalan',
        'show_qr_checklist' => 'Tampilkan Kode QR di Formulir Checklist',
        'notif_saved' => 'Pengaturan tata letak dokumen berhasil disimpan',
    ],

    // =============================================
    // SETTINGS CLUSTER — RENTAL SETTINGS
    // =============================================
    'rental_settings' => [
        'nav_label' => 'Pengaturan Sewa',
        'section_deposit' => 'Pengaturan Deposit',
        'section_late_fee' => 'Pengaturan Denda Keterlambatan',
        'enable_deposit' => 'Aktifkan Deposit',
        'percentage' => 'Persentase (%)',
        'fixed_amount' => 'Jumlah Tetap (Rp)',
        'late_fee_type' => 'Tipe Denda Keterlambatan',
        'percentage_per_day' => 'Persentase per Hari',
        'amount_per_day' => 'Jumlah per Hari',
    ],

    // =============================================
    // SETTINGS CLUSTER — NOTIFICATION SETTINGS
    // =============================================
    'notification_settings' => [
        'nav_label' => 'Notifikasi & WhatsApp',
        'section_channels' => 'Saluran',
        'section_types' => 'Tipe Notifikasi',
        'section_sender' => 'Pengirim Email',
        'section_whatsapp' => 'Template WhatsApp',
        'enable_inapp' => 'Aktifkan Notifikasi Dalam Aplikasi',
        'enable_email' => 'Aktifkan Notifikasi Email',
        'enable_whatsapp' => 'Aktifkan Kirim via WhatsApp',
        'type_new_customer' => 'Registrasi Pelanggan Baru',
        'type_verification' => 'Permintaan Verifikasi Pelanggan',
        'type_new_rental' => 'Pesanan Sewa Baru (Penawaran)',
        'type_new_invoice' => 'Invoice Baru',
        'type_delivery_out' => 'Surat Jalan Keluar',
        'type_delivery_in' => 'Surat Jalan Masuk',
        'type_rental_completed' => 'Sewa Selesai',
        'from_name' => 'Nama Pengirim',
        'from_address' => 'Alamat Pengirim',
        'tpl_rental_detail' => 'Template Detail Sewa',
        'tpl_quotation' => 'Template Penawaran',
        'tpl_invoice' => 'Template Invoice',
        'tpl_delivery_out' => 'Template Surat Jalan (Keluar/Ke Pelanggan)',
        'tpl_delivery_in' => 'Template Surat Jalan (Masuk/Pengembalian)',
        'tpl_pickup_reminder' => 'Template Pengingat Pengambilan',
        'tpl_return_reminder' => 'Template Pengingat Pengembalian',
    ],

    // =============================================
    // SETTINGS CLUSTER — PAYMENT SETTINGS
    // =============================================
    'payment_settings' => [
        'nav_label' => 'Pembayaran',
        'section_manual' => 'Transfer Bank Manual',
        'enable_manual' => 'Aktifkan Transfer Manual',
        'bank_name' => 'Nama Bank',
        'account_number' => 'Nomor Rekening',
        'account_holder' => 'Atas Nama',
        'notif_saved' => 'Pengaturan pembayaran berhasil disimpan',
    ],

    // =============================================
    // SETTINGS CLUSTER — FINANCE SETTINGS
    // =============================================
    'finance_settings' => [
        'nav_label' => 'Keuangan',
        'tab_mode' => 'Mode Keuangan',
        'tab_tax' => 'Pengaturan Pajak',
        'section_mode' => 'Mode Keuangan',
        'section_global_tax' => 'Konfigurasi Pajak Global',
        'section_tax_system' => 'Mode Sistem Pajak',
        'section_company_tax' => 'Identitas Pajak Perusahaan',
        'section_indonesia_tax' => 'Konfigurasi Pajak Indonesia',
        'section_intl_tax' => 'Konfigurasi Pajak Internasional',
        'mode_label' => 'Mode Keuangan',
        'mode_simple' => 'Sederhana (Pemasukan/Pengeluaran)',
        'mode_advanced' => 'Lanjutan (Akuntansi Berpasangan)',
        'enable_tax' => 'Aktifkan Sistem Pajak',
        'select_tax_system' => 'Pilih Sistem Pajak',
        'tax_indonesia' => 'Indonesia (PPN & PPh Final)',
        'tax_international' => 'Internasional (Multi-Tarif Pajak)',
        'company_name_tax' => 'Nama Perusahaan (Pajak)',
        'npwp' => 'NPWP',
        'nik' => 'NIK',
        'tax_address' => 'Alamat Pajak',
        'pkp' => 'Pengusaha Kena Pajak (PKP)',
        'ppn_default' => 'Kena PPN (11%) (Default)',
        'price_inc_tax' => 'Harga Termasuk Pajak (Default)',
        'digital_cert' => 'Sertifikat Digital (e-Faktur)',
        'default_ppn_rate' => 'Tarif PPN Default (%)',
        'pph_final_rate' => 'Tarif PPh Final (%)',
        'country' => 'Negara',
        'tax_name' => 'Nama Pajak',
        'tax_rate' => 'Tarif (%)',
        'add_tax_rate' => 'Tambah Tarif Pajak',
        'notif_advanced_mode' => 'Beralih ke Mode Lanjutan',
        'notif_synced' => ':count transaksi disinkronkan ke Entri Jurnal',
    ],

    // =============================================
    // SETTINGS CLUSTER — PRODUCT SETUP
    // =============================================
    'product_setup' => [
        'nav_label' => 'Pengaturan Produk',
        'tab_brands' => 'Merek',
        'tab_categories' => 'Kategori',
        'add_brand' => 'Tambah Merek Baru',
        'add_category' => 'Tambah Kategori Baru',
        'notif_saved' => 'Pengaturan produk berhasil disimpan',
    ],

    // =============================================
    // SETTINGS CLUSTER — BACKUP & RESTORE
    // =============================================
    'backup' => [
        'nav_label' => 'Backup & Pemulihan',
        'create_backup' => 'Buat Backup',
        'restore_backup' => 'Pulihkan Backup',
        'type_full' => 'Backup Lengkap (Direkomendasikan)',
        'type_products' => 'Produk & Kategori',
        'type_customers' => 'Pelanggan',
        'type_rentals' => 'Penyewaan',
        'type_finance' => 'Keuangan & Invoice',
        'type_settings' => 'Pengaturan & CMS',
        'type_files' => 'File & Media (Gambar, Dokumen)',
        'col_date' => 'Tanggal',
        'col_user' => 'Pengguna',
        'col_type' => 'Tipe',
        'action_download' => 'Unduh',
        'notif_deleted' => 'Backup dihapus',
        'notif_created' => 'Backup Berhasil Dibuat',
        'notif_create_failed' => 'Backup Gagal',
        'notif_restored' => 'Pemulihan Berhasil',
        'notif_restore_failed' => 'Pemulihan Gagal',
    ],

    // =============================================
    // SETTINGS CLUSTER — REGISTRATION SETTINGS
    // =============================================
    'registration' => [
        'nav_label' => 'Registrasi & Verifikasi',
        'section_registration' => 'Pengaturan Registrasi',
        'section_custom_fields' => 'Field Registrasi Kustom',
        'section_verification' => 'Dokumen Verifikasi',
        'accept_registrations' => 'Terima Registrasi Baru',
        'auto_verify_email' => 'Verifikasi Email Otomatis',
        'default_category' => 'Kategori Pelanggan Default',
        'fields' => 'Field',
        'field_key' => 'Kunci Field',
        'field_type_text' => 'Teks',
        'field_type_number' => 'Angka',
        'field_type_select' => 'Pilihan',
        'field_type_radio' => 'Radio',
        'field_type_checkbox' => 'Kotak Centang',
        'field_type_textarea' => 'Textarea',
        'required_field' => 'Field Wajib',
        'document_types' => 'Tipe Dokumen',
        'required_verification' => 'Wajib untuk Verifikasi',
        'add_document_type' => 'Tambah Tipe Dokumen',
    ],

    // =============================================
    // SETUP WIZARD
    // =============================================
    'setup_wizard' => [
        'title' => 'Setup Toko',
        'step1_title' => 'Informasi Toko',
        'step1_description' => 'Atur identitas dan branding toko Anda',
        'step2_title' => 'Jam Operasional',
        'step2_description' => 'Konfigurasi jam buka toko Anda',
        'step3_title' => 'Pembayaran',
        'step3_description' => 'Atur metode pembayaran',
        'step4_title' => 'Data Contoh',
        'step4_description' => 'Import produk contoh sesuai kategori toko',
        'skip_button' => 'Lewati Setup',
        'skip_confirm_title' => 'Lewati Setup?',
        'skip_confirm_desc' => 'Anda bisa mengatur semuanya nanti melalui menu Pengaturan.',
        'complete_button' => 'Selesaikan Setup',
        'banner_message' => 'Setup toko Anda belum selesai. Selesaikan untuk memaksimalkan penggunaan platform.',
        'banner_button' => 'Lanjutkan Setup',
        'import_prompt' => 'Apakah Anda ingin mengimport produk contoh untuk kategori ":category"?',
        'import_description' => 'Sistem akan membuat 3 produk contoh dengan data lengkap untuk membantu Anda memulai.',
        'import_label' => 'Import Data Template',
        'completed_title' => 'Setup Selesai!',
        'completed_body' => 'Toko Anda siap digunakan. Anda bisa mengubah pengaturan kapan saja melalui menu Pengaturan.',
        'logo_label' => 'Logo Toko',
        'store_name' => 'Nama Toko',
        'store_category' => 'Kategori Toko',
        'address' => 'Alamat',
        'phone' => 'Nomor Telepon',
        'email' => 'Email',
        'theme_preset' => 'Tema Warna',
        'enable_bank_transfer' => 'Aktifkan Transfer Bank',
        'bank_name' => 'Nama Bank',
        'account_number' => 'Nomor Rekening',
        'account_holder' => 'Atas Nama',
        'detected_category' => 'Kategori terdeteksi',
        'no_category' => 'Tidak ada kategori dipilih',
    ],

];

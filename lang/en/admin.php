<?php

return [

    // =============================================
    // NAVIGATION GROUPS
    // =============================================
    'nav' => [
        'inventory' => 'Inventory',
        'rentals' => 'Rentals',
        'sales' => 'Sales',
        'system' => 'System',
        'tenant_management' => 'Tenant Management',
        'content_management' => 'Content Management',
        'admin_roles' => 'Admin & Roles',
    ],

    // =============================================
    // COMMON / SHARED LABELS
    // =============================================
    'common' => [
        'active' => 'Active',
        'inactive' => 'Inactive',
        'name' => 'Name',
        'description' => 'Description',
        'status' => 'Status',
        'type' => 'Type',
        'notes' => 'Notes',
        'amount' => 'Amount',
        'date' => 'Date',
        'created' => 'Created',
        'updated' => 'Updated',
        'actions' => 'Actions',
        'save' => 'Save',
        'cancel' => 'Cancel',
        'delete' => 'Delete',
        'edit' => 'Edit',
        'view' => 'View',
        'refresh' => 'Refresh',
        'print' => 'Print',
        'send' => 'Send',
        'category' => 'Category',
        'code' => 'Code',
        'auto_generated' => 'Auto-generated',
        'currency_prefix' => 'Rp',
        'yes' => 'Yes',
        'no' => 'No',
        'add' => 'Add',
        'key' => 'Key',
        'value' => 'Value',
        'required' => 'Required',
        'sort_order' => 'Sort Order',
        'settings_saved' => 'Settings saved successfully',
        'account' => 'Account',
        'payment_date' => 'Payment Date',
        'payment_method' => 'Payment Method',
        'deposit_to_account' => 'Deposit To Account',
    ],

    // =============================================
    // LANGUAGE SETTINGS
    // =============================================
    'language' => [
        'label' => 'Language',
        'indonesian' => 'Bahasa Indonesia',
        'english' => 'English',
        'switched' => 'Language changed to English',
    ],

    // =============================================
    // PAYMENT METHODS
    // =============================================
    'payment_methods' => [
        'cash' => 'Cash',
        'bank_transfer' => 'Bank Transfer',
        'qris' => 'QRIS',
        'credit_card' => 'Credit Card',
    ],

    // =============================================
    // TENANT ADMIN — PRODUCTS
    // =============================================
    'product' => [
        'nav_label' => 'Product Catalog',
        'model_label' => 'Product Catalog',
        'plural_label' => 'Product Catalog',
    ],

    // =============================================
    // TENANT ADMIN — PRODUCT UNITS
    // =============================================
    'product_unit' => [
        'nav_label' => 'Product Unit',
        'product' => 'Product',
        'warehouse' => 'Warehouse',
        'purchase_date' => 'Purchase Date',
        'purchase_price' => 'Purchase Price',
        'residual_value' => 'Residual Value',
        'useful_life' => 'Useful Life (Months)',
        'placeholder_warehouse' => 'Select Warehouse',
        'placeholder_serial' => 'SN-A7IV-001',
        'helper_residual' => 'Estimated value at end of life',
        'suffix_months' => 'months',
        'current_value' => 'Current Value',
        'depreciated_value' => 'Depreciated Value',
        'profit_loss' => 'Profit/Loss',
        'profit_loss_desc' => 'Rev - Maint - Cost',
        'filter_category' => 'Category',
        'status_available' => 'Available',
        'status_scheduled' => 'Scheduled',
        'status_rented' => 'Rented',
        'status_maintenance' => 'Maintenance',
        'status_retired' => 'Retired',
        'condition_excellent' => 'Excellent',
        'condition_good' => 'Good',
        'condition_fair' => 'Fair',
        'condition_poor' => 'Poor',
        'condition_broken' => 'Broken',
        'condition_lost' => 'Lost',
    ],

    // =============================================
    // TENANT ADMIN — RENTALS
    // =============================================
    'rental' => [
        'nav_label' => 'Rentals',
        'badge_quotation' => 'Quotation',
        'badge_late' => 'Late Rental',
    ],

    // =============================================
    // TENANT ADMIN — CUSTOMERS
    // =============================================
    'customer' => [
        'model_label' => 'Customer',
        'plural_label' => 'Customers',
        'nav_label' => 'Customers',
        'badge_need_verification' => 'need verification',
        'email_address' => 'Email address',
        'roles' => 'Roles',
        'verified' => 'Verified',
    ],

    // =============================================
    // TENANT ADMIN — CUSTOMER CATEGORIES
    // =============================================
    'customer_category' => [
        'nav_group' => 'Customer Categories',
    ],

    // =============================================
    // TENANT ADMIN — INVOICES
    // =============================================
    'invoice' => [
        'section_details' => 'Invoice Details',
        'record_payment' => 'Record Payment',
        'add_late_fee' => 'Add Late Fee',
    ],

    // =============================================
    // TENANT ADMIN — QUOTATIONS
    // =============================================
    'quotation' => [
        'section_details' => 'Quotation Details',
        'create_invoice' => 'Create Invoice from this Quotation',
        'invoice_created' => 'Invoice created successfully',
        'status_updated' => 'Status updated',
        'sent' => 'Quotation sent',
    ],

    // =============================================
    // TENANT ADMIN — DELIVERIES
    // =============================================
    'delivery' => [
        'nav_label' => 'Deliveries',
    ],

    // =============================================
    // TENANT ADMIN — DISCOUNTS
    // =============================================
    'discount' => [
        'model_label' => 'Discount Code',
        'plural_label' => 'Discount Codes',
        'section_info' => 'Discount Information',
        'section_limits' => 'Limits',
        'section_validity' => 'Validity',
        'min_rental_amount' => 'Minimum Rental Amount',
        'max_discount_amount' => 'Maximum Discount Amount',
        'total_usage_limit' => 'Total Usage Limit',
        'per_customer_limit' => 'Per Customer Limit',
        'valid_until' => 'Valid Until',
    ],

    // =============================================
    // TENANT ADMIN — DAILY DISCOUNTS
    // =============================================
    'daily_discount' => [
        'model_label' => 'Daily Discount',
        'plural_label' => 'Daily Discounts',
        'section_info' => 'Daily Discount Information',
        'section_validity' => 'Validity',
        'section_desc' => 'Example: Rent 3 days pay 2 days',
        'min_days' => 'Minimum Rental Days',
        'free_days' => 'Free Days',
        'max_discount' => 'Maximum Discount',
        'priority' => 'Priority',
        'start_date' => 'Start Date',
        'end_date' => 'End Date',
        'placeholder_name' => 'Rent 3 Pay 2',
        'placeholder_desc' => 'Promotion description...',
        'helper_min_days' => 'Minimum number of days to get discount',
        'helper_free_days' => 'Number of free days',
        'helper_no_limit' => 'Leave empty for no limit',
        'helper_priority' => 'Higher priority takes precedence',
        'col_min_days' => 'Min. Days',
        'col_free_days' => 'Free Days',
        'col_max_discount' => 'Max. Discount',
        'col_valid_until' => 'Valid Until',
    ],

    // =============================================
    // TENANT ADMIN — DATE PROMOTIONS
    // =============================================
    'date_promotion' => [
        'model_label' => 'Date Promotion',
        'plural_label' => 'Date Promotions',
        'section_info' => 'Date Promotion Information',
        'section_dates' => 'Promo Dates',
        'section_desc' => 'Promotion on specific dates (e.g.: Independence Day, Christmas, etc)',
        'discount_type' => 'Discount Type',
        'discount_value' => 'Discount Value',
        'max_discount' => 'Maximum Discount',
        'priority' => 'Priority',
        'promo_dates' => 'Promo Dates',
        'yearly_recurring' => 'Recurring Every Year',
        'placeholder_name' => 'Independence Day Promo',
        'placeholder_desc' => 'Promotion description...',
        'helper_value' => 'Percentage or amount',
        'helper_no_limit' => 'Leave empty for no limit',
        'helper_priority' => 'Higher priority takes precedence',
        'helper_dates' => 'Specific dates for promo',
        'helper_yearly' => 'Active on the same dates every year',
        'col_promo_dates' => 'Promo Dates',
        'col_type' => 'Type',
        'col_value' => 'Value',
        'col_yearly' => 'Yearly',
    ],

    // =============================================
    // TENANT ADMIN — WAREHOUSES
    // =============================================
    'warehouse' => [
        'nav_label' => 'Warehouse',
        'available_for_rental' => 'Available for Rental',
        'rental_available' => 'Rental Available',
        'helper_rental' => 'If disabled, units in this warehouse cannot be rented.',
    ],

    // =============================================
    // TENANT ADMIN — MAINTENANCE
    // =============================================
    'maintenance' => [
        'label' => 'Maintenance',
        'plural_label' => 'Maintenance & QC',
        'col_product' => 'Product',
        'col_status' => 'Status',
        'col_progress' => 'Maintenance Progress',
        'col_last_qc' => 'Last QC',
        'status_unit_lost' => 'Unit Lost',
        'status_unit_broken' => 'Unit Broken',
        'status_kit_lost' => 'Kit Lost',
        'status_kit_broken' => 'Kit Broken',
        'filter_needs_attention' => 'Needs Attention (Broken/Lost/Maintenance/Kits)',
        'action_manage' => 'Manage',
        'action_qc_passed' => 'QC Passed',
        'action_record_cost' => 'Record Cost',
        'action_update_progress' => 'Update Progress',
        'action_resolve' => 'Resolve Issue',
        'modal_manage' => 'Manage Unit & Kits',
        'unit_condition' => 'Unit Condition',
        'unit_maintenance_status' => 'Unit Maintenance Status',
        'unit_notes' => 'Unit Notes',
        'maintenance_status' => 'Maintenance Status',
        'action_taken' => 'Action Taken',
        'final_unit_condition' => 'Final Unit Condition',
        'kit_final_conditions' => 'Kit Final Conditions',
        'resolution_notes' => 'Resolution Notes',
        'expense_title' => 'Expense Title',
        'source_account' => 'Source Account',
        'maintenance_notes' => 'Maintenance Notes',
        'status_in_repair' => 'In Repair',
        'status_waiting_parts' => 'Waiting Parts',
        'status_ready_qc' => 'Ready for QC',
        'status_waiting_customer' => 'Waiting Customer',
        'resolution_repaired' => 'Repaired (Service)',
        'resolution_replaced' => 'Replaced (New Unit)',
        'resolution_found' => 'Found (Was Lost)',
        'resolution_write_off' => 'Write Off (Retired)',
        'placeholder_expense' => 'e.g. Sparepart Replacement',
        'notif_stock_opname' => 'Stock Opname Recorded',
        'notif_expense' => 'Expense Recorded',
        'notif_resolved' => 'Issue Resolved',
        'notif_updated' => 'Unit and kits updated successfully.',
    ],

    // =============================================
    // TENANT ADMIN — DOCUMENT TYPES
    // =============================================
    'document_type' => [
        'nav_label' => 'Document Types',
        'nav_group' => 'Setting',
        'required_verification' => 'Required for verification',
    ],

    // =============================================
    // TENANT ADMIN — EMAIL LOGS
    // =============================================
    'email_log' => [
        'nav_label' => 'Email Logs',
        'model_label' => 'Email Log',
        'plural_label' => 'Email Logs',
        'recipient' => 'Recipient',
        'error_message' => 'Error Message',
        'sent_at' => 'Sent At',
        'created_at' => 'Created At',
        'error' => 'Error',
        'triggered_by' => 'Triggered By',
        'filter_sent' => 'Sent',
        'filter_failed' => 'Failed',
    ],

    // =============================================
    // TENANT ADMIN — NAVIGATION
    // =============================================
    'navigation' => [
        'location' => 'Location',
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
        'nav_label' => 'Admins & Staff',
    ],

    // =============================================
    // TENANT ADMIN — USERS
    // =============================================
    'user' => [
        'tab_details' => 'User Details',
        'tab_customer' => 'Customer Information',
        'tab_additional' => 'Additional Information',
        'tab_tax' => 'Tax Identity',
        'tab_account' => 'Account',
        'nik_ktp' => 'NIK / KTP',
        'tax_name' => 'Tax Name (Tax Invoice Name)',
        'npwp' => 'NPWP',
        'tax_reg_number' => 'Tax Registration Number (TRN/VAT ID)',
        'tax_entity_type' => 'Tax Entity Type',
        'pkp' => 'PKP (Taxable Entrepreneur)',
        'tax_exempt' => 'Tax Exempt (Zero-Rated)',
        'tax_address' => 'Tax Address',
        'tax_country' => 'Tax Country',
        'verified_customer' => 'Verified Customer',
        'reset_password' => 'Reset Password',
        'placeholder_tax_name' => 'As per NPWP/KTP',
        'placeholder_international' => 'For international customers',
        'helper_pkp' => 'Enable if this customer is a PKP.',
        'helper_tax_exempt' => 'Enable for government entities or export services (No Tax applied).',
        'helper_reset_password' => 'Reset Password to "resetpassword"',
        'entity_personal' => 'Personal',
        'entity_corporate' => 'Corporate',
        'entity_government' => 'Government',
        'modal_reset_confirm' => 'Are you sure you want to reset this user\'s password to "resetpassword"?',
        'modal_reset_heading' => 'Reset Password',
        'country_id' => 'Indonesia',
        'country_sg' => 'Singapore',
        'country_my' => 'Malaysia',
        'country_us' => 'United States',
        'country_uk' => 'United Kingdom',
        'country_au' => 'Australia',
        'country_jp' => 'Japan',
        'country_cn' => 'China',
        'country_in' => 'India',
        'country_th' => 'Thailand',
        'country_vn' => 'Vietnam',
        'country_ph' => 'Philippines',
    ],

    // =============================================
    // CENTRAL ADMIN — TENANTS
    // =============================================
    'tenant' => [
        'tab_tenant' => 'Tenant',
        'tab_business' => 'Business Information',
        'tab_owner' => 'Owner Profile',
        'tab_subscription' => 'Subscription & Status',
        'section_identity' => 'Tenant Identity',
        'section_domains' => 'Domains',
        'section_admin_user' => 'System Admin User',
        'section_subscription' => 'Subscription',
        'status_trial' => 'Trial',
        'status_active' => 'Active',
        'status_inactive' => 'Inactive',
        'status_suspended' => 'Suspended',
        'currency_idr' => 'Indonesia (IDR)',
        'currency_usd' => 'International (USD)',
        'trial_ends' => 'Trial Ends At',
        'subscription_ends' => 'Subscription Ends At',
        'feature_overrides' => 'Feature Overrides',
        'additional_data' => 'Additional Data',
        'custom_data' => 'Custom Data',
        'add_domain' => 'Add Domain',
        'add_data' => 'Add Data',
        'col_id' => 'ID',
        'col_company' => 'Company',
        'col_email' => 'Email',
        'col_plan' => 'Plan',
        'col_expires' => 'Expires',
        'action_access' => 'Access Tenant',
        'action_suspend' => 'Suspend',
        'action_activate' => 'Activate',
        'action_suspend_selected' => 'Suspend Selected',
        'filter_indonesia' => 'Indonesia',
        'filter_international' => 'International',
    ],

    // =============================================
    // CENTRAL ADMIN — TENANT CATEGORIES
    // =============================================
    'tenant_category' => [
        'nav_label' => 'Tenant Categories',
        'section_info' => 'Category Information',
    ],

    // =============================================
    // CENTRAL ADMIN — ADMIN USERS
    // =============================================
    'admin_user' => [
        'nav_label' => 'Admin Users',
        'section_info' => 'User Information',
    ],

    // =============================================
    // CENTRAL ADMIN — SUBSCRIPTION PLANS
    // =============================================
    'subscription_plan' => [
        'section_details' => 'Plan Details',
        'section_pricing' => 'Pricing',
        'section_multi_currency' => 'Multi-Currency Pricing',
        'section_limits' => 'Limits',
        'section_features' => 'Features',
        'section_settings' => 'Settings',
        'monthly_price' => 'Monthly Price',
        'yearly_price' => 'Yearly Price',
        'idr' => 'IDR - Indonesian Rupiah',
        'usd' => 'USD - US Dollar',
        'eur' => 'EUR - Euro',
        'monthly' => 'Monthly',
        'yearly' => 'Yearly',
        'gateway' => 'Gateway',
        'gateway_duitku' => 'Duitku (IDR)',
        'gateway_lemon' => 'LemonSqueezy (USD)',
        'gateway_auto' => 'Auto-detect',
        'max_users' => 'Max Users',
        'max_products' => 'Max Products',
        'max_storage' => 'Max Storage (MB)',
        'max_domains' => 'Max Domains',
        'max_rentals' => 'Max Rental Transactions / Month',
        'featured' => 'Featured',
        'col_users' => 'Users',
        'col_products' => 'Products',
        'col_storage' => 'Storage',
        'col_rental_month' => 'Rental / Month',
        'col_tenants' => 'Tenants',
        'col_order' => 'Order',
    ],

    // =============================================
    // CENTRAL ADMIN — SAAS INVOICES
    // =============================================
    'saas_invoice' => [
        'nav_label' => 'Billing & Invoices',
        'section_details' => 'Invoice Details',
        'section_amounts' => 'Amounts',
        'section_dates' => 'Dates',
        'section_payment' => 'Payment',
        'tenant' => 'Tenant',
        'invoice_number' => 'Invoice Number',
        'subscription' => 'Subscription',
        'status_pending' => 'Pending',
        'status_paid' => 'Paid',
        'status_overdue' => 'Overdue',
        'status_cancelled' => 'Cancelled',
        'currency_idr' => 'IDR',
        'currency_usd' => 'USD',
        'currency_eur' => 'EUR',
        'col_issued' => 'Issued',
        'col_due' => 'Due',
        'col_via' => 'Via',
        'action_mark_paid' => 'Mark as Paid',
        'confirm_mark_paid' => 'Mark Invoice as Paid?',
        'confirm_mark_paid_desc' => 'This action will mark the invoice as paid and activate the tenant subscription.',
    ],

    // =============================================
    // CENTRAL ADMIN — PAYMENT GATEWAYS
    // =============================================
    'payment_gateway' => [
        'nav_label' => 'Payment Gateways',
        'section_identity' => 'Gateway Identity',
        'section_credentials' => 'Credentials',
        'section_callbacks' => 'Callback URLs',
        'section_settings' => 'Settings',
        'merchant_code' => 'Merchant Code',
        'api_key' => 'API Key',
        'callback_url' => 'Callback URL (Notification)',
        'return_url' => 'Return URL (Redirect after payment)',
        'sandbox_mode' => 'Sandbox Mode',
        'col_methods' => 'Methods',
    ],

    // =============================================
    // CENTRAL ADMIN — PAYMENT METHODS (CENTRAL)
    // =============================================
    'payment_method_central' => [
        'nav_label' => 'Payment Methods',
        'section_config' => 'Method Configuration',
        'section_fee' => 'Fee & Settings',
        'fee_type' => 'Fee Type',
        'fee_fixed' => 'Fixed (Rp)',
        'fee_percentage' => 'Percentage (%)',
    ],

    // =============================================
    // CENTRAL ADMIN — TRANSLATIONS
    // =============================================
    'translation' => [
        'nav_label' => 'Translations',
        'section_key' => 'Translation Key',
        'section_translations' => 'Translations',
        'lang_id' => 'Indonesian (ID)',
        'lang_en' => 'English (EN)',
        'col_id' => 'ID',
        'col_en' => 'EN',
    ],

    // =============================================
    // CENTRAL ADMIN — BRANDING SETTINGS
    // =============================================
    'branding' => [
        'nav_label' => 'Branding & SEO',
        'section_identity' => 'Brand Identity',
        'section_seo' => 'SEO & Meta Tags',
        'site_name' => 'Website Name',
        'logo' => 'Logo',
        'favicon' => 'Favicon',
        'site_description' => 'Website Description',
        'meta_keywords' => 'Meta Keywords',
        'og_image' => 'Open Graph Image',
        'notif_saved' => 'Branding settings saved successfully',
        'notif_failed' => 'Failed to save branding settings',
    ],

    // =============================================
    // CENTRAL ADMIN — EMAIL SETTINGS
    // =============================================
    'email_settings' => [
        'nav_label' => 'Email Settings',
        'section_method' => 'Email Delivery Method',
        'section_smtp' => 'SMTP Configuration',
        'section_ses' => 'Amazon SES Configuration',
        'section_sender' => 'Sender Identity',
        'section_test' => 'Test Connection',
        'mailer_smtp' => 'SMTP',
        'mailer_sesv2' => 'Amazon SES v2 (Recommended)',
        'mailer_ses' => 'Amazon SES v1',
        'mailer_mailgun' => 'Mailgun',
        'mailer_postmark' => 'Postmark',
        'mailer_log' => 'Log (Testing Only)',
        'mailer_driver' => 'Mailer / Driver',
        'host' => 'Host',
        'port' => 'Port',
        'username' => 'Username',
        'password' => 'Password',
        'encryption' => 'Encryption',
        'encryption_tls' => 'TLS',
        'encryption_ssl' => 'SSL',
        'encryption_none' => 'None',
        'aws_access_key' => 'AWS Access Key ID',
        'aws_secret_key' => 'AWS Secret Access Key',
        'aws_region' => 'AWS Region',
        'region_singapore' => 'Asia Pacific — Singapore (ap-southeast-1)',
        'region_jakarta' => 'Asia Pacific — Jakarta (ap-southeast-3)',
        'region_tokyo' => 'Asia Pacific — Tokyo (ap-northeast-1)',
        'region_virginia' => 'US East — N. Virginia (us-east-1)',
        'region_oregon' => 'US West — Oregon (us-west-2)',
        'region_ireland' => 'Europe — Ireland (eu-west-1)',
        'region_frankfurt' => 'Europe — Frankfurt (eu-central-1)',
        'from_address' => 'From Address',
        'from_name' => 'From Name',
        'send_test' => 'Send Test Email',
        'notif_saved' => 'Email settings saved successfully',
        'notif_failed' => 'Failed to save email settings',
    ],

    // =============================================
    // CENTRAL ADMIN — R2 STORAGE SETTINGS
    // =============================================
    'r2_storage' => [
        'nav_label' => 'R2 Storage',
        'title' => 'Cloudflare R2 Storage Settings',
        'section_credentials' => 'API Credentials',
        'section_bucket' => 'Bucket Configuration',
        'access_key' => 'Access Key ID',
        'secret_key' => 'Secret Access Key',
        'bucket_name' => 'Bucket Name',
        'endpoint_url' => 'R2 Endpoint URL',
        'public_url' => 'Public URL (Optional)',
        'region' => 'Region',
        'path_style' => 'Use Path Style Endpoint',
        'save_config' => 'Save Configuration',
        'test_connection' => 'Test Connection',
        'notif_saved' => 'R2 configuration saved successfully!',
        'notif_failed' => 'Failed to save configuration',
        'notif_connected' => 'Connection Successful!',
        'notif_connect_failed' => 'Connection Failed',
        'notif_stats_updated' => 'Stats updated',
    ],

    // =============================================
    // CENTRAL ADMIN — SERVER SETTINGS
    // =============================================
    'server' => [
        'section_app' => 'Application Settings',
        'section_db_cache' => 'Database & Cache',
        'app_name' => 'Application Name',
        'environment' => 'Environment',
        'debug_mode' => 'Debug Mode',
        'app_url' => 'Application URL',
        'db_driver' => 'Database Driver',
        'cache_driver' => 'Cache Driver',
        'session_driver' => 'Session Driver',
        'queue_driver' => 'Queue Driver',
        'php_version' => 'PHP Version',
        'laravel_version' => 'Laravel Version',
        'storage_permissions' => 'Storage Permissions',
        'cache' => 'Cache',
        'storage_symlink' => 'Storage Symlink',
        'failed_jobs' => 'Failed Jobs',
        'clear_cache' => 'Clear Cache',
        'deep_clean' => 'Deep Clean',
        'optimize' => 'Optimize',
        'fix_storage_link' => 'Fix Storage Link',
        'retry_failed_jobs' => 'Retry Failed Jobs',
        'notif_cache_cleared' => 'Cache Cleared',
        'notif_cache_cleared_desc' => 'All caches have been cleared successfully.',
        'notif_deep_clean' => 'Deep Cache Clean Complete',
        'notif_optimized' => 'Application Optimized',
        'notif_optimized_desc' => 'Application has been optimized for production.',
        'notif_storage_fixed' => 'Storage Link Fixed',
        'notif_storage_fixed_desc' => 'The storage symlink has been created successfully.',
        'notif_jobs_retried' => 'Failed Jobs Retried',
        'notif_jobs_retried_desc' => 'All failed jobs have been queued for retry.',
    ],

    // =============================================
    // CENTRAL ADMIN — DATABASE MANAGEMENT
    // =============================================
    'database' => [
        'migrate_central' => 'Migrate Central',
        'migrate_tenants' => 'Migrate All Tenants',
        'notif_central_complete' => 'Central Migration Complete',
        'notif_tenant_complete' => 'Tenant Migration Complete',
        'notif_tenant_complete_desc' => 'All tenant databases have been migrated.',
        'notif_refreshed' => 'Refreshed',
    ],

    // =============================================
    // CENTRAL ADMIN — R2 FILE BROWSER
    // =============================================
    'file_browser' => [
        'nav_label' => 'File Browser',
        'title' => 'R2 File Browser',
        'notif_deleted' => 'Item deleted successfully',
        'notif_delete_failed' => 'Failed to delete item',
        'notif_refreshed' => 'Data refreshed',
    ],

    // =============================================
    // CENTRAL ADMIN — STORAGE MANAGEMENT
    // =============================================
    'storage' => [
        'total_space' => 'Total Space',
        'free_space' => 'Free Space',
        'used_space' => 'Used Space',
        'usage_percentage' => 'Usage Percentage',
        'logs_size' => 'Logs Size',
        'cache_size' => 'Cache Size',
        'sessions_size' => 'Sessions Size',
        'views_cache_size' => 'Views Cache Size',
        'clear_logs' => 'Clear Logs',
        'clear_sessions' => 'Clear Sessions',
        'notif_logs_cleared' => 'Logs Cleared',
        'notif_logs_cleared_desc' => 'All log files have been deleted.',
        'notif_sessions_cleared' => 'Sessions Cleared',
        'notif_sessions_cleared_desc' => 'All session files have been deleted.',
        'notif_refreshed' => 'Refreshed',
        'notif_refreshed_desc' => 'Storage information has been refreshed.',
    ],

    // =============================================
    // FINANCE CLUSTER — FINANCE ACCOUNTS
    // =============================================
    'finance_account' => [
        'nav_label' => 'Cash & Bank',
        'model_label' => 'Cash & Bank',
    ],

    // =============================================
    // FINANCE CLUSTER — FINANCE TRANSACTIONS
    // =============================================
    'finance_transaction' => [
        'nav_label' => 'Journal Entries',
    ],

    // =============================================
    // FINANCE CLUSTER — JOURNAL ENTRIES
    // =============================================
    'journal_entry' => [
        'nav_label' => 'Journal Entries',
        'model_label' => 'Journal Entry',
        'plural_label' => 'Journal Entries',
        'section_details' => 'Entry Details',
        'section_items' => 'Journal Items',
        'current_balance' => 'Current Balance',
        'add_item' => 'Add Item',
        'filter_account' => 'Filter by Account',
    ],

    // =============================================
    // FINANCE CLUSTER — ACCOUNT MAPPINGS
    // =============================================
    'account_mapping' => [
        'nav_label' => 'Journal Mappings',
        'model_label' => 'Mapping',
        'plural_label' => 'Journal Mappings',
        'event_invoice_receivable' => 'Invoice Created (Receivable)',
        'event_invoice_revenue' => 'Invoice Created (Revenue)',
        'event_invoice_tax' => 'Invoice Created (Tax)',
        'event_receive_payment' => 'Receive Payment (Cash/Bank)',
        'event_deposit_received' => 'Security Deposit Received (Cash)',
        'event_deposit_refunded' => 'Security Deposit Refunded',
        'event_expense' => 'Expense Recorded',
        'side_debit' => 'Debit',
        'side_credit' => 'Credit',
        'col_code' => 'Code',
        'col_account_name' => 'Account Name',
    ],

    // =============================================
    // FINANCE CLUSTER — CHART OF ACCOUNTS
    // =============================================
    'chart_of_accounts' => [
        'nav_label' => 'Chart of Accounts',
        'model_label' => 'Account',
        'plural_label' => 'Chart of Accounts',
        'account_code' => 'Account Code',
        'account_name' => 'Account Name',
        'parent_account' => 'Parent Account',
        'sub_type' => 'Sub Type',
        'is_sub_account' => 'Is Sub Account',
        'type_asset' => 'Asset',
        'type_liability' => 'Liability',
        'type_equity' => 'Equity',
        'type_revenue' => 'Revenue',
        'type_expense' => 'Expense',
        'col_parent' => 'Parent',
        'col_sub' => 'Sub',
    ],

    // =============================================
    // FINANCE CLUSTER — BILLS (ACCOUNTS PAYABLE)
    // =============================================
    'bill' => [
        'nav_label' => 'Accounts Payable',
        'section_details' => 'Bill Details',
        'bill_number' => 'Bill / Invoice Number',
        'category_utilities' => 'Utilities',
        'category_inventory' => 'Inventory',
        'category_service' => 'Service',
        'category_rent' => 'Rent',
        'category_other' => 'Other',
        'col_due' => 'Due',
        'status_pending' => 'Pending',
        'status_partial' => 'Partial',
        'status_paid' => 'Paid',
        'status_overdue' => 'Overdue',
        'action_pay' => 'Pay',
        'pay_from_account' => 'Pay From Account',
        'payment_amount' => 'Payment Amount',
        'notif_payment_recorded' => 'Payment Recorded',
    ],

    // =============================================
    // FINANCE CLUSTER — EXPENSES
    // =============================================
    'expense' => [
        'nav_label' => 'Operational Expenses',
        'section_details' => 'Expense Details',
        'paid_from_account' => 'Paid From Account',
        'category_operational' => 'Operational',
        'category_utilities' => 'Utilities',
        'category_salary' => 'Salary',
        'category_maintenance' => 'Maintenance',
        'category_fuel' => 'Fuel',
        'category_marketing' => 'Marketing',
        'category_other' => 'Other',
        'col_account' => 'Account',
        'col_recorded_by' => 'Recorded By',
    ],

    // =============================================
    // FINANCE CLUSTER — ACCOUNTS RECEIVABLE PAGE
    // =============================================
    'accounts_receivable' => [
        'nav_label' => 'Accounts Receivable',
        'col_customer' => 'Customer',
        'col_due' => 'Due',
        'action_record_payment' => 'Record Payment',
        'notif_payment_recorded' => 'Payment Recorded',
    ],

    // =============================================
    // FINANCE CLUSTER — CUSTOMER DEPOSITS PAGE
    // =============================================
    'customer_deposit' => [
        'nav_label' => 'Customer Deposits',
        'title' => 'Customer Deposits Control',
        'col_customer' => 'Customer',
        'col_required' => 'Required Deposit',
        'col_held' => 'Held Amount',
        'action_receive' => 'Receive',
        'action_refund' => 'Refund',
        'amount_received' => 'Amount Received',
        'refund_amount' => 'Refund Amount',
        'deduction' => 'Deduction (Damage/Late)',
        'refund_notes' => 'Refund Notes',
        'status_pending' => 'Pending',
        'status_held' => 'Held',
        'status_refunded' => 'Refunded',
        'status_forfeited' => 'Forfeited',
        'notif_received' => 'Deposit Received',
        'notif_refunded' => 'Deposit Refunded',
    ],

    // =============================================
    // FINANCE CLUSTER — FINANCIAL REPORTS PAGE
    // =============================================
    'financial_reports' => [
        'nav_label' => 'Reports',
    ],

    // =============================================
    // SETTINGS CLUSTER — GENERAL SETTINGS
    // =============================================
    'general_settings' => [
        'nav_label' => 'Business Information',
        'logo' => 'Logo',
        'site_name' => 'Site Name',
        'site_description' => 'Site Description',
        'company_name' => 'Company Name',
        'address' => 'Address',
        'phone' => 'Phone',
        'email' => 'Email',
    ],

    // =============================================
    // SETTINGS CLUSTER — APPEARANCE SETTINGS
    // =============================================
    'appearance' => [
        'nav_label' => 'Appearance',
        'theme_preset' => 'Theme Preset',
        'custom_color' => 'Custom Color',
        'nav_layout' => 'Navigation Layout',
        'layout_sidebar' => 'Sidebar',
        'layout_top' => 'Top Navigation',
        'preset_default' => 'Default',
        'preset_slate' => 'Slate',
        'preset_gray' => 'Gray',
        'preset_zinc' => 'Zinc',
        'preset_neutral' => 'Neutral',
        'preset_stone' => 'Stone',
        'preset_red' => 'Red',
        'preset_orange' => 'Orange',
        'preset_amber' => 'Amber',
        'preset_yellow' => 'Yellow',
        'preset_lime' => 'Lime',
        'preset_green' => 'Green',
        'preset_emerald' => 'Emerald',
        'preset_teal' => 'Teal',
        'preset_cyan' => 'Cyan',
        'preset_sky' => 'Sky',
        'preset_blue' => 'Blue',
        'preset_indigo' => 'Indigo',
        'preset_violet' => 'Violet',
        'preset_purple' => 'Purple',
        'preset_fuchsia' => 'Fuchsia',
        'preset_pink' => 'Pink',
        'preset_rose' => 'Rose',
        'preset_custom' => 'Custom',
    ],

    // =============================================
    // SETTINGS CLUSTER — DOCUMENT LAYOUT
    // =============================================
    'doc_layout' => [
        'nav_label' => 'Document Layout',
        'tab_invoice' => 'Invoice',
        'tab_quotation' => 'Quotation',
        'tab_delivery' => 'Delivery Note',
        'tab_checklist' => 'Checklist Form',
        'tab_branding' => 'Branding & Style',
        'tab_company' => 'Company Information',
        'tab_content' => 'Document Content',
        'tab_qr' => 'QR Code',
        'section_visual' => 'Visual Identity',
        'section_colors' => 'Colors',
        'section_table' => 'Table Options',
        'section_company' => 'Company Information',
        'section_qr' => 'QR Code Visibility',
        'doc_logo' => 'Document Logo',
        'show_logo' => 'Show Logo on Documents',
        'font_family' => 'Font Family',
        'font_dejavu' => 'DejaVu Sans (Default)',
        'font_helvetica' => 'Helvetica',
        'font_arial' => 'Arial',
        'font_times' => 'Times New Roman',
        'font_courier' => 'Courier',
        'primary_color' => 'Primary Color',
        'secondary_color' => 'Secondary Color',
        'striped_rows' => 'Striped Rows',
        'bordered_table' => 'Bordered Table',
        'company_name' => 'Company Name',
        'phone' => 'Phone',
        'email' => 'Email',
        'website' => 'Website',
        'tax_id' => 'Tax ID / NPWP',
        'address' => 'Address',
        'custom_header' => 'Custom Header Text',
        'custom_footer' => 'Custom Footer Text',
        'quotation_terms' => 'Quotation Terms & Conditions',
        'bank_details' => 'Bank Account Details',
        'show_qr_delivery' => 'Show QR Code on Delivery Note',
        'show_qr_checklist' => 'Show QR Code on Checklist Form',
        'notif_saved' => 'Document layout settings saved successfully',
    ],

    // =============================================
    // SETTINGS CLUSTER — RENTAL SETTINGS
    // =============================================
    'rental_settings' => [
        'nav_label' => 'Rental Settings',
        'section_deposit' => 'Deposit Settings',
        'section_late_fee' => 'Late Fee Settings',
        'enable_deposit' => 'Enable Deposit',
        'percentage' => 'Percentage (%)',
        'fixed_amount' => 'Fixed Amount (Rp)',
        'late_fee_type' => 'Late Fee Type',
        'percentage_per_day' => 'Percentage per Day',
        'amount_per_day' => 'Amount per Day',
    ],

    // =============================================
    // SETTINGS CLUSTER — NOTIFICATION SETTINGS
    // =============================================
    'notification_settings' => [
        'nav_label' => 'Notification & WhatsApp',
        'section_channels' => 'Channels',
        'section_types' => 'Notification Types',
        'section_sender' => 'Email Sender',
        'section_whatsapp' => 'WhatsApp Templates',
        'enable_inapp' => 'Enable In-App Notifications',
        'enable_email' => 'Enable Email Notifications',
        'enable_whatsapp' => 'Enable Send via WhatsApp',
        'type_new_customer' => 'New Customer Registration',
        'type_verification' => 'Customer Verification Request',
        'type_new_rental' => 'New Rental Order (Quotation)',
        'type_new_invoice' => 'New Invoice',
        'type_delivery_out' => 'Delivery Out',
        'type_delivery_in' => 'Delivery In',
        'type_rental_completed' => 'Rental Completed',
        'from_name' => 'From Name',
        'from_address' => 'From Address',
        'tpl_rental_detail' => 'Rental Detail Template',
        'tpl_quotation' => 'Quotation Template',
        'tpl_invoice' => 'Invoice Template',
        'tpl_delivery_out' => 'Delivery (Out/To Customer) Template',
        'tpl_delivery_in' => 'Delivery (In/Return) Template',
        'tpl_pickup_reminder' => 'Pickup Reminder Template',
        'tpl_return_reminder' => 'Return Reminder Template',
    ],

    // =============================================
    // SETTINGS CLUSTER — PAYMENT SETTINGS
    // =============================================
    'payment_settings' => [
        'nav_label' => 'Payment',
        'section_manual' => 'Manual Bank Transfer',
        'enable_manual' => 'Enable Manual Transfer',
        'bank_name' => 'Bank Name',
        'account_number' => 'Account Number',
        'account_holder' => 'Account Holder Name',
        'notif_saved' => 'Payment settings saved successfully',
    ],

    // =============================================
    // SETTINGS CLUSTER — FINANCE SETTINGS
    // =============================================
    'finance_settings' => [
        'nav_label' => 'Finance',
        'tab_mode' => 'Finance Mode',
        'tab_tax' => 'Tax Settings',
        'section_mode' => 'Finance Mode',
        'section_global_tax' => 'Global Tax Configuration',
        'section_tax_system' => 'Tax System Mode',
        'section_company_tax' => 'Company Tax Identity',
        'section_indonesia_tax' => 'Indonesia Tax Configuration',
        'section_intl_tax' => 'International Tax Configuration',
        'mode_label' => 'Finance Mode',
        'mode_simple' => 'Simple (Income/Expense)',
        'mode_advanced' => 'Advanced (Double Entry Accounting)',
        'enable_tax' => 'Enable Tax System',
        'select_tax_system' => 'Select Tax System',
        'tax_indonesia' => 'Indonesia (PPN & PPh Final)',
        'tax_international' => 'International (Multi-Tax Rates)',
        'company_name_tax' => 'Company Name (Tax)',
        'npwp' => 'NPWP',
        'nik' => 'NIK',
        'tax_address' => 'Tax Address',
        'pkp' => 'Taxable Entrepreneur (PKP)',
        'ppn_default' => 'PPN Applied (11%) (Default)',
        'price_inc_tax' => 'Price Includes Tax (Default)',
        'digital_cert' => 'Digital Certificate (e-Faktur)',
        'default_ppn_rate' => 'Default PPN Rate (%)',
        'pph_final_rate' => 'PPh Final Rate (%)',
        'country' => 'Country',
        'tax_name' => 'Tax Name',
        'tax_rate' => 'Rate (%)',
        'add_tax_rate' => 'Add Tax Rate',
        'notif_advanced_mode' => 'Switched to Advanced Mode',
        'notif_synced' => 'Synced :count transactions to Journal Entries',
    ],

    // =============================================
    // SETTINGS CLUSTER — PRODUCT SETUP
    // =============================================
    'product_setup' => [
        'nav_label' => 'Product Setup',
        'tab_brands' => 'Brands',
        'tab_categories' => 'Categories',
        'add_brand' => 'Add New Brand',
        'add_category' => 'Add New Category',
        'notif_saved' => 'Product setup saved successfully',
    ],

    // =============================================
    // SETTINGS CLUSTER — BACKUP & RESTORE
    // =============================================
    'backup' => [
        'nav_label' => 'Backup & Restore',
        'create_backup' => 'Create Backup',
        'restore_backup' => 'Restore Backup',
        'type_full' => 'Full Backup (Recommended)',
        'type_products' => 'Products & Categories',
        'type_customers' => 'Customers',
        'type_rentals' => 'Rentals',
        'type_finance' => 'Finance & Invoices',
        'type_settings' => 'Settings & CMS',
        'type_files' => 'Files & Media (Images, Documents)',
        'col_date' => 'Date',
        'col_user' => 'User',
        'col_type' => 'Type',
        'action_download' => 'Download',
        'notif_deleted' => 'Backup deleted',
        'notif_created' => 'Backup Created Successfully',
        'notif_create_failed' => 'Backup Failed',
        'notif_restored' => 'Restore Completed Successfully',
        'notif_restore_failed' => 'Restore Failed',
    ],

    // =============================================
    // SETTINGS CLUSTER — REGISTRATION SETTINGS
    // =============================================
    'registration' => [
        'nav_label' => 'Registration & Verification',
        'section_registration' => 'Registration Settings',
        'section_custom_fields' => 'Custom Registration Fields',
        'section_verification' => 'Verification Documents',
        'accept_registrations' => 'Accept New Registrations',
        'auto_verify_email' => 'Auto Verify Email',
        'default_category' => 'Default Customer Category',
        'fields' => 'Fields',
        'field_key' => 'Field Key',
        'field_type_text' => 'Text',
        'field_type_number' => 'Number',
        'field_type_select' => 'Select',
        'field_type_radio' => 'Radio',
        'field_type_checkbox' => 'Checkbox',
        'field_type_textarea' => 'Textarea',
        'required_field' => 'Required Field',
        'document_types' => 'Document Types',
        'required_verification' => 'Required for Verification',
        'add_document_type' => 'Add Document Type',
    ],

];

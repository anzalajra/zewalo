<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;

class SettingsSyncService
{
    /**
     * Sync general/company settings to document layout settings.
     */
    public static function syncToDocumentLayout(array $data): void
    {
        $mapping = [
            'site_logo' => 'doc_logo',
            'site_name' => 'doc_company_name',
            'company_name' => 'doc_company_name',
            'company_address' => 'doc_company_address',
            'company_phone' => 'doc_company_phone',
            'company_email' => 'doc_company_email',
        ];

        foreach ($mapping as $source => $target) {
            if (isset($data[$source]) && $data[$source] !== null && $data[$source] !== '') {
                Setting::set($target, $data[$source]);
            }
        }
    }
}

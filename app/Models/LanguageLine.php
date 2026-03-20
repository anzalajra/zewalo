<?php

declare(strict_types=1);

namespace App\Models;

use Spatie\TranslationLoader\LanguageLine as SpatieLanguageLine;

class LanguageLine extends SpatieLanguageLine
{
    /**
     * Always use central database for translations,
     * even when in tenant context.
     */
    protected $connection = 'central';
}

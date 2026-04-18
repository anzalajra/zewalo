<?php

namespace App\Models;

use App\Models\Central\TenantTemplate;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TenantCategory extends Model
{
    protected $connection = 'central';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }

    public function template(): HasOne
    {
        return $this->hasOne(TenantTemplate::class);
    }

    public function activeTemplate(): HasOne
    {
        return $this->hasOne(TenantTemplate::class)->where('is_active', true);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}

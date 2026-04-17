<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantIssue extends Model
{
    use HasFactory;

    protected $connection = 'central';

    protected $table = 'tenant_issues';

    protected $fillable = [
        'tenant_id',
        'tenant_name',
        'code',
        'area',
        'severity',
        'title',
        'message',
        'exception_class',
        'file',
        'line',
        'stack_trace',
        'context',
        'url',
        'user_email',
        'resolved_at',
        'resolved_by',
        'resolution_note',
    ];

    protected $casts = [
        'context' => 'array',
        'line' => 'integer',
        'resolved_at' => 'datetime',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function isResolved(): bool
    {
        return $this->resolved_at !== null;
    }

    public function markResolved(?string $by = null, ?string $note = null): void
    {
        $this->update([
            'resolved_at' => now(),
            'resolved_by' => $by,
            'resolution_note' => $note,
        ]);
    }

    public function scopeUnresolved($query)
    {
        return $query->whereNull('resolved_at');
    }
}

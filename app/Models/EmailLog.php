<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmailLog extends Model
{
    protected $fillable = [
        'to',
        'subject',
        'mailable_class',
        'status',
        'error_message',
        'sent_at',
        'user_id',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log an email being sent
     */
    public static function logSent(string $to, string $subject, ?string $mailableClass = null, ?int $userId = null): self
    {
        return self::create([
            'to' => $to,
            'subject' => $subject,
            'mailable_class' => $mailableClass,
            'status' => 'sent',
            'sent_at' => now(),
            'user_id' => $userId,
        ]);
    }

    /**
     * Log a failed email
     */
    public static function logFailed(string $to, string $subject, ?string $mailableClass = null, ?string $errorMessage = null, ?int $userId = null): self
    {
        return self::create([
            'to' => $to,
            'subject' => $subject,
            'mailable_class' => $mailableClass,
            'status' => 'failed',
            'error_message' => $errorMessage,
            'user_id' => $userId,
        ]);
    }

    /**
     * Scope for sent emails
     */
    public function scopeSent($query)
    {
        return $query->where('status', 'sent');
    }

    /**
     * Scope for failed emails
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}

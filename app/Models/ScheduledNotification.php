<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ScheduledNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'notification_class',
        'notifiable_id',
        'notifiable_type',
        'sent',
        'processing',
        'scheduled_at',
        'sent_at',
        'tries',
    ];


    protected $casts = [
        'sent' => 'boolean',
        'processing' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }
}

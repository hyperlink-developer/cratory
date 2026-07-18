<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NotificationLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'notifiable_type',
        'notifiable_id',
        'document_type',
        'document_id',
        'notification_id',
        'channel',
        'status',
        'error_message',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function notifiable(): MorphTo
    {
        return $this->morphTo();
    }

    public function document(): MorphTo
    {
        return $this->morphTo();
    }
}

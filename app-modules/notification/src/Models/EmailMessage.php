<?php

namespace AdvisingApp\Notification\Models;

use AdvisingApp\Notification\Models\Contracts\Message;
use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class EmailMessage extends BaseModel implements Message
{
    protected $fillable = [
        'notification_class',
        'external_reference_id',
        'content',
        'quota_usage',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function related(): MorphTo
    {
        return $this->morphTo(
            name: 'related',
            type: 'related_type',
            id: 'related_id',
        );
    }

    public function recipient(): MorphTo
    {
        return $this->morphTo(
            name: 'recipient',
            type: 'recipient_type',
            id: 'recipient_id',
        );
    }

    public function events(): HasMany
    {
        return $this->hasMany(EmailMessageEvent::class);
    }
}

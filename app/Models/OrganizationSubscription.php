<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrganizationSubscription extends Model
{
    protected $fillable = [
        'organization_id',
        'subscription_plan_id',
        'razorpay_subscription_id',
        'status',
        'current_period_end',
    ];

    protected function casts(): array
    {
        return [
            'current_period_end' => 'datetime',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }
}

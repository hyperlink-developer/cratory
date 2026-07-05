<?php

namespace App\Traits;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToOrganization
{
    public static function bootBelongsToOrganization(): void
    {
        static::addGlobalScope('organization', function (Builder $builder) {
            if ($organizationId = static::resolveCurrentOrganizationId()) {
                $builder->where($builder->getModel()->getTable() . '.organization_id', $organizationId);
            }
        });

        static::creating(function (Model $model) {
            if (empty($model->organization_id)) {
                $model->organization_id = static::resolveCurrentOrganizationId();
            }
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    protected static function resolveCurrentOrganizationId(): ?int
    {
        if (app()->runningInConsole() && !app()->runningUnitTests()) {
            return null;
        }

        return session('current_organization_id')
            ?? auth()->user()?->current_organization_id;
    }
}

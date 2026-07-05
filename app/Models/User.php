<?php

namespace App\Models;

use App\Enums\OrgUserRole;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasUuid;

    protected $fillable = [
        'name',
        'email',
        'password',
        'is_commander',
        'phone',
        'avatar_path',
        'current_organization_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_commander' => 'boolean',
        ];
    }

    // Relationships

    public function organizations(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot(['role', 'is_default_org', 'status'])
            ->withTimestamps();
    }

    public function currentOrganization(): BelongsTo
    {
        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    public function createdOrganizations()
    {
        return $this->hasMany(Organization::class, 'created_by');
    }

    // Helpers

    public function isCommander(): bool
    {
        return $this->is_commander;
    }

    public function switchOrganization(int $organizationId): void
    {
        $isMember = $this->organizations()
            ->where('organizations.id', $organizationId)
            ->where('organization_user.status', 'active')
            ->exists();

        if ($isMember) {
            $this->update(['current_organization_id' => $organizationId]);
            session(['current_organization_id' => $organizationId]);
        }
    }

    public function roleInOrganization(?int $organizationId = null): ?OrgUserRole
    {
        $orgId = $organizationId ?? $this->current_organization_id;

        if (!$orgId) {
            return null;
        }

        $pivot = $this->organizations()
            ->where('organizations.id', $orgId)
            ->first()?->pivot;

        return $pivot ? OrgUserRole::from($pivot->role) : null;
    }

    public function hasOrganizations(): bool
    {
        return $this->organizations()->exists();
    }

    public function activeOrganizations()
    {
        return $this->organizations()
            ->where('organization_user.status', 'active')
            ->where('organizations.is_active', true);
    }
}

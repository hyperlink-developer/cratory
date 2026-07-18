<?php

namespace App\Models;

use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warehouse extends Model
{
    use HasFactory, BelongsToOrganization, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'pincode',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(WarehouseStock::class);
    }
}

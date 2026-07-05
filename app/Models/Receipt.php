<?php

namespace App\Models;

use App\Enums\PaymentMode;
use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Receipt extends Model
{
    use BelongsToOrganization, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id', 'receipt_number', 'contact_id', 'receipt_date',
        'amount', 'payment_mode', 'reference_number', 'notes', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'receipt_date' => 'date',
            'amount' => 'decimal:2',
            'payment_mode' => PaymentMode::class,
        ];
    }

    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function allocations(): HasMany { return $this->hasMany(ReceiptAllocation::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
}

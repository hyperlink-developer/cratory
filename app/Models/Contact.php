<?php

namespace App\Models;

use App\Enums\ContactType;
use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Contact extends Model
{
    use BelongsToOrganization, HasFactory, HasUuid, Notifiable, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'type',
        'name',
        'display_name',
        'gst_number',
        'pan_number',
        'billing_address_line_1',
        'billing_address_line_2',
        'billing_city',
        'billing_state',
        'billing_pincode',
        'billing_country',
        'shipping_address_line_1',
        'shipping_address_line_2',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
        'shipping_country',
        'phone',
        'email',
        'opening_balance',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContactType::class,
            'opening_balance' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    public function receipts(): HasMany
    {
        return $this->hasMany(Receipt::class);
    }

    public function paymentVouchers(): HasMany
    {
        return $this->hasMany(PaymentVoucher::class);
    }

    // Scopes

    public function scopeCustomers($query)
    {
        return $query->whereIn('type', [ContactType::Customer, ContactType::Both]);
    }

    public function scopeVendors($query)
    {
        return $query->whereIn('type', [ContactType::Vendor, ContactType::Both]);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers

    public function getDisplayNameAttribute($value): string
    {
        return $value ?: $this->name;
    }

    public function isCustomer(): bool
    {
        return in_array($this->type, [ContactType::Customer, ContactType::Both]);
    }

    public function isVendor(): bool
    {
        return in_array($this->type, [ContactType::Vendor, ContactType::Both]);
    }
}

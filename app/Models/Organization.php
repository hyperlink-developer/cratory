<?php

namespace App\Models;

use App\Enums\BusinessCategory;
use App\Enums\OrganizationType;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasUuid, SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'business_category',
        'pan_number',
        'gst_number',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'pincode',
        'country',
        'phone',
        'email',
        'logo_path',
        'invoice_prefix',
        'document_settings',
        'financial_year_start_month',
        'currency',
        'created_by',
        'is_active',
        'is_composition_tax_payer',
    ];

    protected function casts(): array
    {
        return [
            'type' => OrganizationType::class,
            'business_category' => BusinessCategory::class,
            'is_active' => 'boolean',
            'is_composition_tax_payer' => 'boolean',
            'financial_year_start_month' => 'integer',
            'document_settings' => 'array',
        ];
    }

    // Relationships

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot(['role', 'is_default_org', 'status'])
            ->withTimestamps();
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

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

    public function invoiceTemplates(): HasMany
    {
        return $this->hasMany(InvoiceTemplate::class);
    }

    public function taxRates(): HasMany
    {
        return $this->hasMany(TaxRate::class);
    }

    public function productCategories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

    public function documentSequences(): HasMany
    {
        return $this->hasMany(DocumentSequence::class);
    }

    // Scopes

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Helpers

    public function isServiceOnly(): bool
    {
        return $this->business_category === BusinessCategory::Service;
    }

    public function showsPurchases(): bool
    {
        return $this->business_category->showsPurchases();
    }

    public function showsInventory(): bool
    {
        return $this->business_category->showsInventory();
    }

    public function getOrgStateCode(): ?string
    {
        if ($this->gst_number && strlen($this->gst_number) >= 2) {
            return substr($this->gst_number, 0, 2);
        }
        return null;
    }
}

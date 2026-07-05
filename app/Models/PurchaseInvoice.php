<?php

namespace App\Models;

use App\Enums\PurchaseStatus;
use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoice extends Model
{
    use BelongsToOrganization, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id', 'invoice_basis', 'purchase_number', 'vendor_bill_number',
        'contact_id', 'purchase_date', 'due_date',
        'subtotal', 'discount_total', 'tax_total', 'round_off', 'grand_total',
        'amount_paid', 'balance_due', 'status', 'attachment_path', 'created_by',
    ];

    protected function casts(): array
    {
        return [
            'invoice_basis' => \App\Enums\InvoiceBasis::class,
            'status' => PurchaseStatus::class,
            'purchase_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount_total' => 'decimal:2',
            'tax_total' => 'decimal:2',
            'round_off' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'amount_paid' => 'decimal:2',
            'balance_due' => 'decimal:2',
        ];
    }

    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function items(): HasMany { return $this->hasMany(PurchaseInvoiceItem::class); }
    public function voucherAllocations(): HasMany { return $this->hasMany(PaymentVoucherAllocation::class); }
    public function stockMovements(): MorphMany { return $this->morphMany(StockMovement::class, 'reference'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }

    public function recalculateAmountPaid(): void
    {
        $this->amount_paid = $this->voucherAllocations()->sum('allocated_amount');
        $this->balance_due = $this->grand_total - $this->amount_paid;
        $this->status = $this->balance_due <= 0 ? PurchaseStatus::Paid
            : ($this->amount_paid > 0 ? PurchaseStatus::Partial : $this->status);
        $this->save();
    }
}

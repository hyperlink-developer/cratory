<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use App\Enums\InvoiceType;
use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use BelongsToOrganization, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'invoice_basis',
        'invoice_type',
        'invoice_number',
        'contact_id',
        'invoice_date',
        'due_date',
        'template_id',
        'subtotal',
        'discount_total',
        'tax_total',
        'round_off',
        'grand_total',
        'amount_paid',
        'balance_due',
        'status',
        'notes',
        'terms_and_conditions',
        'place_of_supply',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'invoice_basis' => \App\Enums\InvoiceBasis::class,
            'invoice_type' => InvoiceType::class,
            'status' => InvoiceStatus::class,
            'invoice_date' => 'date',
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

    // Relationships

    public function contact(): BelongsTo
    {
        return $this->belongsTo(Contact::class);
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(InvoiceTemplate::class, 'template_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function receiptAllocations(): HasMany
    {
        return $this->hasMany(ReceiptAllocation::class);
    }

    public function stockMovements(): MorphMany
    {
        return $this->morphMany(StockMovement::class, 'reference');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    public function scopeSales($query)
    {
        return $query->where('invoice_type', InvoiceType::Sales);
    }

    public function scopeService($query)
    {
        return $query->where('invoice_type', InvoiceType::Service);
    }

    public function scopeByStatus($query, InvoiceStatus $status)
    {
        return $query->where('status', $status);
    }

    // Helpers

    public function isSales(): bool
    {
        return $this->invoice_type === InvoiceType::Sales;
    }

    public function isService(): bool
    {
        return $this->invoice_type === InvoiceType::Service;
    }

    public function isDraft(): bool
    {
        return $this->status === InvoiceStatus::Draft;
    }

    public function isPaid(): bool
    {
        return $this->status === InvoiceStatus::Paid;
    }

    public function isCancelled(): bool
    {
        return $this->status === InvoiceStatus::Cancelled;
    }

    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items->sum('line_total');
        $this->discount_total = $this->items->sum('discount_amount');
        $this->tax_total = $this->items->sum('tax_amount');

        $raw = $this->subtotal + $this->tax_total;
        $this->round_off = round($raw) - $raw;
        $this->grand_total = round($raw);
        $this->balance_due = $this->grand_total - $this->amount_paid;
    }

    public function recalculateAmountPaid(): void
    {
        $this->amount_paid = $this->receiptAllocations()->sum('allocated_amount');
        $this->balance_due = $this->grand_total - $this->amount_paid;

        if ($this->balance_due <= 0) {
            $this->status = InvoiceStatus::Paid;
        } elseif ($this->amount_paid > 0) {
            $this->status = InvoiceStatus::Partial;
        }

        $this->save();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseInvoiceItem extends Model
{
    protected $fillable = [
        'purchase_invoice_id', 'product_id', 'description', 'hsn_code',
        'quantity', 'unit', 'rate', 'discount_percent', 'discount_amount',
        'tax_rate_id', 'tax_amount', 'line_total',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2', 'rate' => 'decimal:2',
            'discount_percent' => 'decimal:2', 'discount_amount' => 'decimal:2',
            'tax_amount' => 'decimal:2', 'line_total' => 'decimal:2',
        ];
    }

    public function purchaseInvoice(): BelongsTo { return $this->belongsTo(PurchaseInvoice::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function taxRate(): BelongsTo { return $this->belongsTo(TaxRate::class); }

    public function calculateTotals(): void
    {
        $subtotal = $this->quantity * $this->rate;
        if ($this->discount_percent > 0) {
            $this->discount_amount = round($subtotal * $this->discount_percent / 100, 2);
        }
        $taxableAmount = $subtotal - $this->discount_amount;
        $this->tax_amount = round($taxableAmount * ($this->taxRate?->percentage ?? 0) / 100, 2);
        $this->line_total = $taxableAmount + $this->tax_amount;
    }
}

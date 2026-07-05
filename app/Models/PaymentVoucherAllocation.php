<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentVoucherAllocation extends Model
{
    protected $fillable = ['payment_voucher_id', 'purchase_invoice_id', 'allocated_amount'];

    protected function casts(): array
    {
        return ['allocated_amount' => 'decimal:2'];
    }

    public function paymentVoucher(): BelongsTo { return $this->belongsTo(PaymentVoucher::class); }
    public function purchaseInvoice(): BelongsTo { return $this->belongsTo(PurchaseInvoice::class); }
}

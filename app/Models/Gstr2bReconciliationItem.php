<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Gstr2bReconciliationItem extends Model
{
    protected $fillable = [
        'organization_id',
        'gst_report_period_id',
        'purchase_invoice_id',
        'uploaded_gstin',
        'uploaded_invoice_number',
        'uploaded_invoice_date',
        'uploaded_taxable_value',
        'uploaded_tax_amount',
        'match_status',
    ];

    protected function casts(): array
    {
        return [
            'uploaded_invoice_date' => 'date',
            'uploaded_taxable_value' => 'decimal:2',
            'uploaded_tax_amount' => 'decimal:2',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(GstReportPeriod::class, 'gst_report_period_id');
    }

    public function purchaseInvoice(): BelongsTo
    {
        return $this->belongsTo(PurchaseInvoice::class);
    }
}

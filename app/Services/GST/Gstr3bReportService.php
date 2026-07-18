<?php

namespace App\Services\GST;

use App\Enums\InvoiceStatus;
use App\Enums\PurchaseStatus;
use App\Models\GstReportPeriod;
use App\Models\Invoice;
use App\Models\PurchaseInvoice;

class Gstr3bReportService
{
    protected GstReportPeriod $period;

    public function __construct(GstReportPeriod $period)
    {
        $this->period = $period;
    }

    public function getSummary(): array
    {
        // Outward Supplies (Sales)
        $salesInvoices = Invoice::where('organization_id', $this->period->organization_id)
            ->whereBetween('invoice_date', [$this->period->period_start, $this->period->period_end])
            ->where('status', '!=', InvoiceStatus::Draft)
            ->where('status', '!=', InvoiceStatus::Cancelled)
            ->get();

        $totalSalesTaxableValue = $salesInvoices->sum('subtotal') - $salesInvoices->sum('discount_total') - $salesInvoices->sum('tax_total');
        $totalSalesTaxLiability = $salesInvoices->sum('tax_total');

        // Input Tax Credit (Purchases)
        $purchaseInvoices = PurchaseInvoice::where('organization_id', $this->period->organization_id)
            ->whereBetween('purchase_date', [$this->period->period_start, $this->period->period_end])
            ->where('status', '!=', PurchaseStatus::Draft)
            ->where('status', '!=', PurchaseStatus::Cancelled)
            ->get();

        $totalPurchaseTaxableValue = $purchaseInvoices->sum('subtotal') - $purchaseInvoices->sum('discount_total') - $purchaseInvoices->sum('tax_total');
        $totalItcAvailable = $purchaseInvoices->sum('tax_total');

        return [
            'outward_supplies' => [
                'taxable_value' => round($totalSalesTaxableValue, 2),
                'tax_liability' => round($totalSalesTaxLiability, 2),
            ],
            'inward_supplies' => [
                'taxable_value' => round($totalPurchaseTaxableValue, 2),
                'itc_available' => round($totalItcAvailable, 2),
            ],
            'net_tax_payable' => round($totalSalesTaxLiability - $totalItcAvailable, 2),
        ];
    }
}

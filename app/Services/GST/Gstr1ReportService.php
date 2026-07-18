<?php

namespace App\Services\GST;

use App\Enums\InvoiceStatus;
use App\Models\GstReportPeriod;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Gstr1ReportService
{
    protected GstReportPeriod $period;

    public function __construct(GstReportPeriod $period)
    {
        $this->period = $period;
    }

    /**
     * Get B2B Invoices (Business to Business - Customer has GSTIN).
     * Grouped by customer GSTIN, with invoice-wise breakup.
     */
    public function getB2bInvoices(): Collection
    {
        return Invoice::with(['contact', 'items.taxRate'])
            ->where('organization_id', $this->period->organization_id)
            ->whereBetween('invoice_date', [$this->period->period_start, $this->period->period_end])
            ->where('status', '!=', InvoiceStatus::Draft)
            ->where('status', '!=', InvoiceStatus::Cancelled)
            ->whereHas('contact', function ($query) {
                $query->whereNotNull('gst_number')->where('gst_number', '!=', '');
            })
            ->get()
            ->map(function ($invoice) {
                $taxableValue = $invoice->items->sum(function ($item) {
                    return $item->line_total - $item->tax_amount;
                });
                $taxAmount = $invoice->items->sum('tax_amount');
                
                // Assuming standard split: if intra-state CGST/SGST (50% each), if inter-state IGST (100%)
                // We'll simplify and return total tax here, Excel export can split based on place_of_supply if needed.
                
                return [
                    'gstin' => $invoice->contact->gst_number,
                    'receiver_name' => $invoice->contact->display_name,
                    'invoice_number' => $invoice->invoice_number,
                    'invoice_date' => $invoice->invoice_date->format('d-M-y'),
                    'invoice_value' => $invoice->grand_total,
                    'place_of_supply' => $invoice->place_of_supply,
                    'reverse_charge' => 'N',
                    'invoice_type' => 'Regular',
                    'rate' => $invoice->items->first()?->taxRate?->percentage ?? 0,
                    'taxable_value' => round($taxableValue, 2),
                    'tax_amount' => round($taxAmount, 2),
                ];
            });
    }

    /**
     * Get B2C Invoices (Business to Consumer - Customer has no GSTIN).
     * Aggregated by tax rate (and place of supply).
     */
    public function getB2cInvoices(): Collection
    {
        $invoices = Invoice::with(['contact', 'items.taxRate'])
            ->where('organization_id', $this->period->organization_id)
            ->whereBetween('invoice_date', [$this->period->period_start, $this->period->period_end])
            ->where('status', '!=', InvoiceStatus::Draft)
            ->where('status', '!=', InvoiceStatus::Cancelled)
            ->whereHas('contact', function ($query) {
                $query->whereNull('gst_number')->orWhere('gst_number', '');
            })
            ->get();

        $b2cData = collect();

        foreach ($invoices as $invoice) {
            foreach ($invoice->items as $item) {
                $rate = $item->taxRate?->percentage ?? 0;
                $placeOfSupply = $invoice->place_of_supply ?? 'Other';
                $key = $rate . '-' . $placeOfSupply;

                $taxableValue = $item->line_total - $item->tax_amount;

                if (!$b2cData->has($key)) {
                    $b2cData->put($key, [
                        'place_of_supply' => $placeOfSupply,
                        'rate' => $rate,
                        'taxable_value' => 0,
                        'tax_amount' => 0,
                    ]);
                }

                $existing = $b2cData->get($key);
                $existing['taxable_value'] += $taxableValue;
                $existing['tax_amount'] += $item->tax_amount;
                $b2cData->put($key, $existing);
            }
        }

        return $b2cData->values();
    }

    /**
     * HSN-wise summary of all items.
     */
    public function getHsnSummary(): Collection
    {
        $invoiceIds = Invoice::where('organization_id', $this->period->organization_id)
            ->whereBetween('invoice_date', [$this->period->period_start, $this->period->period_end])
            ->where('status', '!=', InvoiceStatus::Draft)
            ->where('status', '!=', InvoiceStatus::Cancelled)
            ->pluck('id');

        $items = InvoiceItem::with('taxRate')
            ->whereIn('invoice_id', $invoiceIds)
            ->get();

        $hsnData = collect();

        foreach ($items as $item) {
            $hsn = $item->hsn_code ?? 'OTHER';
            
            $taxableValue = $item->line_total - $item->tax_amount;

            if (!$hsnData->has($hsn)) {
                $hsnData->put($hsn, [
                    'hsn_sc' => $hsn,
                    'description' => $item->item_name,
                    'uqc' => $item->unit ?? 'OTH',
                    'total_quantity' => 0,
                    'total_value' => 0,
                    'taxable_value' => 0,
                    'integrated_tax_amount' => 0,
                    'central_tax_amount' => 0,
                    'state_ut_tax_amount' => 0,
                    'cess_amount' => 0,
                    'tax_amount' => 0,
                ]);
            }

            $existing = $hsnData->get($hsn);
            $existing['total_quantity'] += $item->quantity;
            $existing['total_value'] += $item->line_total;
            $existing['taxable_value'] += $taxableValue;
            $existing['tax_amount'] += $item->tax_amount;
            // Simplified split logic: usually determined by place of supply of the invoice. 
            // We'll leave the granular split for export logic or next iteration.
            
            $hsnData->put($hsn, $existing);
        }

        return $hsnData->values();
    }
}

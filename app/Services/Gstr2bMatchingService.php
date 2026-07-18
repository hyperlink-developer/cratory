<?php

namespace App\Services;

use App\Models\Gstr2bReconciliationItem;
use App\Models\GstReportPeriod;
use App\Models\PurchaseInvoice;
use League\Csv\Reader;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Gstr2bMatchingService
{
    /**
     * Process a GSTR-2B CSV file and match against local Purchase Invoices.
     */
    public function processCsvUpload(string $filePath, GstReportPeriod $period): void
    {
        $csv = Reader::createFromPath($filePath, 'r');
        $csv->setHeaderOffset(0);

        // Delete any existing reconciliation items for this period before processing a new file
        Gstr2bReconciliationItem::where('gst_report_period_id', $period->id)->delete();

        $records = $csv->getRecords();

        DB::transaction(function () use ($records, $period) {
            foreach ($records as $record) {
                // Handle different possible column names from GSTN
                $gstin = $record['GSTIN of Supplier'] ?? $record['GSTIN'] ?? null;
                $invoiceNumber = $record['Invoice number'] ?? $record['Invoice No'] ?? null;
                $invoiceDateStr = $record['Invoice date'] ?? $record['Invoice Date'] ?? null;
                
                // Remove commas and convert to float
                $taxableValueStr = str_replace(',', '', $record['Taxable Value'] ?? $record['Taxable value'] ?? '0');
                $taxAmountStr = str_replace(',', '', $record['Total Tax'] ?? $record['Tax Amount'] ?? '0');

                // Some CSVs split CGST/SGST/IGST, so we might need to sum them up if Total Tax isn't present
                if (!isset($record['Total Tax']) && !isset($record['Tax Amount'])) {
                    $cgst = (float)str_replace(',', '', $record['Central Tax'] ?? '0');
                    $sgst = (float)str_replace(',', '', $record['State/UT Tax'] ?? '0');
                    $igst = (float)str_replace(',', '', $record['Integrated Tax'] ?? '0');
                    $taxAmount = $cgst + $sgst + $igst;
                } else {
                    $taxAmount = (float)$taxAmountStr;
                }

                $taxableValue = (float)$taxableValueStr;
                
                $invoiceDate = null;
                if ($invoiceDateStr) {
                    try {
                        $invoiceDate = Carbon::parse($invoiceDateStr)->format('Y-m-d');
                    } catch (\Exception $e) {
                        $invoiceDate = null;
                    }
                }

                // If essential data is missing, skip row
                if (!$gstin || !$invoiceNumber) {
                    continue;
                }

                $matchStatus = 'unmatched';
                $purchaseInvoiceId = null;

                // Try to find matching purchase invoice
                // Match by Organization, Period matching might not be strict for date, so we check all invoices for this org
                $localInvoice = PurchaseInvoice::where('organization_id', $period->organization_id)
                    ->where('vendor_bill_number', $invoiceNumber)
                    ->whereHas('contact', function ($query) use ($gstin) {
                        $query->where('gst_number', $gstin);
                    })
                    ->first();

                if ($localInvoice) {
                    $purchaseInvoiceId = $localInvoice->id;
                    
                    // Allow small tolerance in float comparison (e.g. 1 rupee)
                    $localTaxable = (float)$localInvoice->subtotal;
                    if (abs($localTaxable - $taxableValue) <= 1.0) {
                        $matchStatus = 'matched';
                    } else {
                        $matchStatus = 'manual_review';
                    }
                }

                Gstr2bReconciliationItem::create([
                    'organization_id' => $period->organization_id,
                    'gst_report_period_id' => $period->id,
                    'purchase_invoice_id' => $purchaseInvoiceId,
                    'uploaded_gstin' => $gstin,
                    'uploaded_invoice_number' => $invoiceNumber,
                    'uploaded_taxable_value' => $taxableValue,
                    'uploaded_tax_amount' => $taxAmount,
                    'match_status' => $matchStatus,
                ]);
            }
        });
    }
}

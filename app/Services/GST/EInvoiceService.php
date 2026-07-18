<?php

namespace App\Services\GST;

use App\Models\Invoice;

class EInvoiceService
{
    protected GspProviderInterface $gsp;

    public function __construct(GspProviderInterface $gsp)
    {
        $this->gsp = $gsp;
    }

    public function generateIrn(Invoice $invoice): bool
    {
        if ($invoice->irn) {
            return false; // Already has IRN
        }

        // 1. Build NIC Schema Payload
        // In a real app, this maps the Invoice model to the strict NIC JSON schema.
        $payload = $this->buildNicPayload($invoice);

        // 2. Call GSP
        $result = $this->gsp->generateIrn($payload);

        // 3. Update Invoice
        $invoice->update([
            'irn' => $result['irn'],
            'irn_status' => 'generated',
            'ack_no' => $result['ack_no'],
            'ack_date' => $result['ack_date'],
            'signed_qr_code' => $result['signed_qr_code'],
            'signed_invoice' => $result['signed_invoice'],
        ]);

        return true;
    }

    public function cancelIrn(Invoice $invoice, string $reason, string $remark): bool
    {
        if (!$invoice->irn || $invoice->irn_status === 'cancelled') {
            return false;
        }

        $success = $this->gsp->cancelIrn($invoice->irn, $reason, $remark);

        if ($success) {
            $invoice->update([
                'irn_status' => 'cancelled',
                'cancel_reason' => $reason,
            ]);
        }

        return $success;
    }

    public function generateEWayBill(Invoice $invoice): bool
    {
        if ($invoice->eway_bill_number) {
            return false;
        }

        $payload = [
            'irn' => $invoice->irn,
            'distance' => 100, // example
            'transporterId' => $invoice->transporter_id,
            'vehicleNo' => $invoice->vehicle_number,
        ];

        $result = $this->gsp->generateEWayBill($payload);

        $invoice->update([
            'eway_bill_number' => $result['eway_bill_number'],
            'eway_bill_date' => $result['eway_bill_date'],
            'eway_bill_valid_until' => $result['valid_until'],
        ]);

        return true;
    }

    protected function buildNicPayload(Invoice $invoice): array
    {
        $org = $invoice->organization;
        $contact = $invoice->contact;

        $itemList = [];
        $index = 1;
        $totalTaxable = 0;
        $totalTax = 0;

        foreach ($invoice->items as $item) {
            $taxable = $item->quantity * $item->rate - $item->discount_amount;
            $cgst = 0;
            $sgst = 0;
            $igst = 0;

            // Simple inter-state logic: check if POS matches Org State
            $isInterState = $invoice->place_of_supply !== $org->getOrgStateCode();

            if ($isInterState) {
                $igst = $item->tax_amount;
            } else {
                $cgst = $item->tax_amount / 2;
                $sgst = $item->tax_amount / 2;
            }

            $itemList[] = [
                'SlNo' => (string) $index++,
                'PrdDesc' => $item->item_name,
                'IsServc' => 'N',
                'HsnCd' => $item->hsn_code ?? '999999',
                'Qty' => (float) $item->quantity,
                'Unit' => $item->unit ?? 'NOS',
                'UnitPrice' => (float) $item->rate,
                'TotAmt' => (float) ($item->quantity * $item->rate),
                'Discount' => (float) $item->discount_amount,
                'AssAmt' => (float) $taxable,
                'GstRt' => (float) ($item->taxRate?->percentage ?? 0),
                'IgstAmt' => (float) $igst,
                'CgstAmt' => (float) $cgst,
                'SgstAmt' => (float) $sgst,
                'CesRt' => 0,
                'CesAmt' => 0,
                'CesNonAdvlAmt' => 0,
                'StateCesRt' => 0,
                'StateCesAmt' => 0,
                'StateCesNonAdvlAmt' => 0,
                'OthChrg' => 0,
                'TotItemVal' => (float) $item->line_total,
            ];

            $totalTaxable += $taxable;
            $totalTax += $item->tax_amount;
        }

        $igstTotal = $isInterState ? $totalTax : 0;
        $cgstTotal = !$isInterState ? $totalTax / 2 : 0;
        $sgstTotal = !$isInterState ? $totalTax / 2 : 0;

        return [
            'Version' => '1.1',
            'TranDtls' => [
                'TaxSch' => 'GST',
                'SupTyp' => 'B2B',
                'RegRev' => 'N',
                'IgstOnIntra' => 'N',
            ],
            'DocDtls' => [
                'Typ' => 'INV',
                'No' => $invoice->invoice_number,
                'Dt' => $invoice->invoice_date->format('d/m/Y'),
            ],
            'SellerDtls' => [
                'Gstin' => $org->gst_number ?? '33AAAAA0000A1Z5',
                'LglNm' => $org->name,
                'Addr1' => $org->address_line_1 ?? 'Address',
                'Loc' => $org->city ?? 'City',
                'Pin' => (int) ($org->pincode ?? 600001),
                'Stcd' => $org->getOrgStateCode() ?? '33',
            ],
            'BuyerDtls' => [
                'Gstin' => $contact->gst_number ?? '33BBBBB0000B1Z5',
                'LglNm' => $contact->name,
                'Pos' => $invoice->place_of_supply ?? '33',
                'Addr1' => $contact->billing_address_line_1 ?? 'Address',
                'Loc' => $contact->billing_city ?? 'City',
                'Pin' => (int) ($contact->billing_pincode ?? 600001),
                'Stcd' => substr($contact->gst_number ?? '33', 0, 2),
            ],
            'ItemList' => $itemList,
            'ValDtls' => [
                'AssVal' => (float) $totalTaxable,
                'CgstVal' => (float) $cgstTotal,
                'SgstVal' => (float) $sgstTotal,
                'IgstVal' => (float) $igstTotal,
                'CesVal' => 0,
                'StCesVal' => 0,
                'Discount' => (float) $invoice->discount_total,
                'OthChrg' => 0,
                'RndOffAmt' => (float) $invoice->round_off,
                'TotInvVal' => (float) $invoice->grand_total,
            ],
        ];
    }
}

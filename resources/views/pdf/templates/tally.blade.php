<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? 'Draft' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
        }
        body {
            font-family: '{{ $template->font_choice ?? 'Helvetica' }}', Arial, sans-serif;
            color: #000;
            font-size: 11px;
            line-height: 1.3;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            box-sizing: border-box;
        }
        
        .tally-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        td, th {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }
        
        .no-border-table td, .no-border-table th {
            border: none;
        }
        
        .tally-main-table {
            border: 1px solid #000;
        }

        .border-bottom { border-bottom: 1px solid #000; }
        .border-right { border-right: 1px solid #000; }
        .border-none { border: none !important; }
        .border-top { border-top: 1px solid #000; }

        .company-name {
            font-weight: bold;
            font-size: 14px;
        }
        
        .small-text {
            font-size: 9px;
            color: #333;
        }
        
        .strong {
            font-weight: bold;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        /* Nested tables for the specific Tally grid */
        .inner-grid {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .inner-grid td {
            border-top: none;
            border-bottom: 1px solid #000;
            border-left: none;
            border-right: 1px solid #000;
        }
        .inner-grid td:last-child {
            border-right: none;
        }
        .inner-grid tr:last-child td {
            border-bottom: none;
        }

        .items-header th {
            text-align: center;
            border-bottom: 1px solid #000;
            background-color: #f9f9f9;
        }
        
        .items-row td {
            border-bottom: none;
            border-top: none;
        }
    </style>
</head>
<body>
    @include('pdf.templates.partials.watermark')
    @php
        $title = strtoupper($invoice->invoice_type->value) . ' INVOICE';
        if ($organization->gst_number) {
            if ($organization->is_composition_tax_payer) {
                $title = 'BILL OF SUPPLY';
            } else {
                $title = $invoice->invoice_type->value === 'sales' ? 'TAX INVOICE' : 'SERVICE INVOICE';
            }
        } else {
            $title = 'INVOICE';
        }
    @endphp

    <table style="width: 100%; height: 100%; border: none; border-collapse: collapse;">
        <tr style="height: 1%;">
            <td style="border: none; padding: 0; text-align: center; padding-bottom: 5px;">
                <div class="tally-title" style="margin: 0;">{{ $title }}</div>
            </td>
        </tr>
        <tr>
            <td style="border: none; padding: 0; vertical-align: top;">
                <table class="tally-main-table" style="height: 100%;">
                    <!-- ROW 1 -->
        <tr style="height: 1%;">
            <!-- Seller Box -->
            <td style="width: 50%; padding: 5px;">
                @include('pdf.templates.partials.logo', ['fallbackClass' => 'company-name'])
                <div>{{ $organization->address_line_1 }}</div>
                @if($organization->address_line_2)<div>{{ $organization->address_line_2 }}</div>@endif
                <div>{{ $organization->city }} {{ $organization->state }} {{ $organization->pincode }}</div>
                <div>{{ $organization->country }}</div>
                <br>
                @if($organization->gst_number)
                    <div>GSTIN/UIN: <span class="strong">{{ $organization->gst_number }}</span></div>
                @endif
                @if($organization->pan_number)
                    <div>PAN: <span class="strong">{{ $organization->pan_number }}</span></div>
                @endif
            </td>
            
            <!-- Invoice Details Box -->
            <td style="width: 50%; padding: 0; border: none; border-bottom: 1px solid #000;">
                <table class="inner-grid" style="height: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <div class="small-text">Invoice No.</div>
                            <div class="strong">{{ $invoice->invoice_number ?? 'DRAFT' }}</div>
                        </td>
                        <td style="width: 50%;">
                            <div class="small-text">Dated</div>
                            <div class="strong">{{ $invoice->invoice_date?->format('d-M-Y') }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="small-text">Delivery Note</div>
                            <div>&nbsp;</div>
                        </td>
                        <td>
                            <div class="small-text">Mode/Terms of Payment</div>
                            <div>{{ $invoice->terms ?? 'As per terms' }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="small-text">Reference No. & Date.</div>
                            <div>&nbsp;</div>
                        </td>
                        <td>
                            <div class="small-text">Other Reference(s)</div>
                            <div>&nbsp;</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- ROW 2 -->
        <tr style="height: 1%;">
            <!-- Buyer Box -->
            <td style="padding: 5px;">
                <div class="small-text">Buyer (Bill to)</div>
                <div class="company-name">{{ $contact->name }}</div>
                <div>{{ $contact->billing_address_line_1 }}</div>
                @if($contact->billing_address_line_2)<div>{{ $contact->billing_address_line_2 }}</div>@endif
                <div>{{ $contact->billing_city }} {{ $contact->billing_state }} {{ $contact->billing_pincode }}</div>
                <div>{{ $contact->billing_country }}</div>
                <br>
                @if($contact->gst_number)
                    <div>GSTIN/UIN: <span class="strong">{{ $contact->gst_number }}</span></div>
                @endif
            </td>
            
            <!-- Dispatch Box -->
            <td style="padding: 0; border: none; border-top: 1px solid #000; border-bottom: 1px solid #000;">
                <table class="inner-grid" style="height: 100%;">
                    <tr>
                        <td style="width: 50%;">
                            <div class="small-text">Buyer's Order No.</div>
                            <div>&nbsp;</div>
                        </td>
                        <td style="width: 50%;">
                            <div class="small-text">Dated</div>
                            <div>&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="small-text">Dispatch Doc No.</div>
                            <div>&nbsp;</div>
                        </td>
                        <td>
                            <div class="small-text">Delivery Note Date</div>
                            <div>&nbsp;</div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="small-text">Dispatched through</div>
                            <div>&nbsp;</div>
                        </td>
                        <td>
                            <div class="small-text">Destination</div>
                            <div>{{ $contact->shipping_city ?? $contact->billing_city ?? '&nbsp;' }}</div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- ROW 2 - Items List -->
        <tr>
            <td colspan="2" style="padding: 0; border: none; vertical-align: top;">
                <table class="inner-grid items-header" style="height: 100%;">
                    <tr class="items-header">
                        <th style="width: 5%; border-top: none; border-left: none;">Sl<br>No.</th>
                        <th style="width: 40%; border-top: none;">Description of Goods</th>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <th style="width: 10%; border-top: none;">HSN/SAC</th>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <th style="width: 10%; border-top: none;">Quantity</th>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <th style="width: 10%; border-top: none;">Rate</th>
                        <th style="width: 5%; border-top: none;">per</th>
                        @endif
                        <th style="width: 20%; border-top: none; border-right: none;">Amount</th>
                    </tr>
                    
                    @php $sno = 1; @endphp
                    @foreach($invoice->items as $item)
                    <tr class="items-row" style="height: 1%;">
                        <td class="text-center" style="border-left: none;">{{ $sno++ }}</td>
                        <td>
                            <span class="strong">{{ $item->product?->name ?? $item->item_name }}</span><br>
                            <span class="small-text">{{ $item->description }}</span>
                        </td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td class="text-center">{{ $item->hsn_code ?? '' }}</td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td class="text-center"><span class="strong">{{ $item->quantity + 0 }}</span> {{ $item->unit }}</td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                        <td class="text-center">{{ $item->unit }}</td>
                        @endif
                        <td class="text-right strong" style="border-right: none;">{{ number_format($item->line_total, 2) }}</td>
                    </tr>
                    @endforeach

                    <!-- Spacers to make table look full -->
                    <tr class="items-row" style="height: 100%;">
                        <td style="height: 100%; border-left: none;"></td>
                        <td></td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td></td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td></td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td></td>
                        <td></td>
                        @endif
                        <td style="border-right: none;"></td>
                    </tr>

                    <!-- Footer Totals row inside Items table to match columns -->
                    <tr>
                        <td colspan="2" class="text-right strong" style="border-left: none; border-bottom: none;">Subtotal</td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td style="border-bottom: none;"></td>
                        <td style="border-bottom: none;"></td>
                        @endif
                        <td class="text-right strong" style="border-bottom: none; border-right: none;">{{ number_format($invoice->subtotal, 2) }}</td>
                    </tr>
                    @if($invoice->discount_total > 0)
                    <tr>
                        <td colspan="2" class="text-right" style="border-left: none; border-bottom: none;">Discount</td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td style="border-bottom: none;"></td>
                        <td style="border-bottom: none;"></td>
                        @endif
                        <td class="text-right" style="border-bottom: none; border-right: none;">-{{ number_format($invoice->discount_total, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->tax_total > 0)
                    <tr>
                        <td colspan="2" class="text-right" style="border-left: none; border-bottom: none;">Tax</td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td style="border-bottom: none;"></td>
                        <td style="border-bottom: none;"></td>
                        @endif
                        <td class="text-right" style="border-bottom: none; border-right: none;">{{ number_format($invoice->tax_total, 2) }}</td>
                    </tr>
                    @endif
                    @if($invoice->round_off != 0)
                    <tr>
                        <td colspan="2" class="text-right" style="border-left: none; border-bottom: none;">Round Off</td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td style="border-bottom: none;"></td>
                        <td style="border-bottom: none;"></td>
                        @endif
                        <td class="text-right" style="border-bottom: none; border-right: none;">{{ number_format($invoice->round_off, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" class="text-right strong" style="border-left: none; border-bottom: none;">Total</td>
                        @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                        <td style="border-bottom: none;"></td>
                        @endif
                        @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                        <td style="border-bottom: none;"></td>
                        <td style="border-bottom: none;"></td>
                        @endif
                        <td class="text-right strong" style="border-bottom: none; border-right: none;">₹ {{ number_format($invoice->grand_total, 2) }}</td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- AMOUNT IN WORDS -->
        <tr style="height: 1%;">
            <td colspan="2" style="border-top: 1px solid #000; padding: 5px;">
                <div class="small-text">Amount Chargeable (in words)</div>
                <div class="strong">INR {{ number_format($invoice->grand_total, 2) }} Only</div>
            </td>
        </tr>

        @if($invoice->payment_info)
        <!-- REMARKS & TERMS -->
        <tr style="height: 1%;">
            <td style="border-top: 1px solid #000; padding: 5px;" colspan="2">
                <div class="small-text text-decoration-underline">Company's Bank Details</div>
                <div style="white-space: pre-wrap;">{{ $invoice->payment_info }}</div>
            </td>
        </tr>
        @endif

        <!-- SIGNATURE BLOCK -->
        <tr style="height: 1%;">
            <td style="width: 50%; padding: 5px; vertical-align: top;">
                @if($invoice->terms_and_conditions)
                <div class="small-text text-decoration-underline" style="margin-bottom: 3px;">Declaration</div>
                <div style="white-space: pre-wrap; margin-bottom: 10px;">{{ $invoice->terms_and_conditions }}</div>
                @endif
                
                @if($template->footer_note)
                    <div style="vertical-align: bottom;">{{ $template->footer_note }}</div>
                @endif
            </td>
            <td style="width: 50%; padding: 5px; text-align: right; vertical-align: bottom;">
                <div class="strong">for {{ $organization->name }}</div>
                @include('pdf.templates.partials.signature', ['align' => 'flex-end'])
                <div class="small-text">Authorised Signatory</div>
            </td>
        </tr>
                </table>
            </td>
        </tr>
        <tr style="height: 1%;">
            <td style="border: none; padding: 0;">
                <div class="text-center small-text" style="margin-top: 10px;">
                    SUBJECT TO {{ strtoupper($organization->city ?? 'OUR') }} JURISDICTION
                </div>
                <div class="text-center small-text" style="margin-top: 5px;">
                    This is a Computer Generated Invoice
                </div>
            </td>
        </tr>
    </table>
</body>
</html>

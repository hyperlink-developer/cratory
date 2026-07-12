<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? 'Draft' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: {{ $template->color_primary ?? '#1d4ed8' }};
        }
        
        html, body {
            height: 100%;
        }
        body {
            font-family: '{{ $template->font_choice ?? 'Helvetica' }}', Arial, sans-serif;
            color: #000;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            box-sizing: border-box;
        }
        
        /* Wrapper table to push footer to bottom */
        .wrapper-table { width: 100%; height: 100%; border: none; border-collapse: collapse; }
        .wrapper-table > tbody > tr > td { border: none; padding: 0; }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .blue-border {
            border: 1px solid var(--primary);
        }
        
        .blue-text {
            color: var(--primary);
        }
        
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .strong { font-weight: bold; }
        
        .header-table td {
            vertical-align: top;
            border: none;
            padding: 0;
        }
        
        .org-logo-text {
            font-size: 32px;
            font-weight: bold;
            color: #eab308; /* Yellow/Orange accent like the YB logo */
            line-height: 1;
            margin-right: 10px;
            float: left;
        }
        
        .org-name {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .contact-info {
            text-align: right;
        }
        .contact-info strong {
            display: inline-block;
            min-width: 60px;
        }
        
        .invoice-title-bar {
            margin-top: 20px;
            background-color: #eff6ff; /* light blue */
            border: 1px solid var(--primary);
            border-bottom: none;
            padding: 5px 10px;
            font-size: 18px;
            font-weight: bold;
            color: var(--primary);
            position: relative;
        }
        .invoice-title-bar .right-text {
            position: absolute;
            right: 10px;
            top: 8px;
            font-size: 10px;
            color: #000;
        }
        
        .details-grid {
            border: 1px solid var(--primary);
            width: 100%;
        }
        
        .details-grid td {
            border: 1px solid var(--primary);
            vertical-align: top;
        }
        
        .details-grid .header-cell {
            background-color: #eff6ff;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            padding: 3px;
        }
        
        .customer-table { width: 100%; border: none; }
        .customer-table td { border: none; padding: 2px 5px; }
        .customer-table td:first-child { font-weight: bold; width: 100px; }
        
        .invoice-info-table { width: 100%; border: none; }
        .invoice-info-table td { border: none; padding: 2px 5px; }
        
        .items-table {
            width: 100%;
            border: 1px solid var(--primary);
            border-top: none;
        }
        .items-table th, .items-table td {
            border-right: 1px solid var(--primary);
            padding: 5px;
        }
        .items-table th:last-child, .items-table td:last-child {
            border-right: none;
        }
        .items-table th {
            background-color: #eff6ff;
            font-weight: bold;
            font-size: 10px;
            border-bottom: 1px solid var(--primary);
        }
        .items-table td {
            border-bottom: none;
            border-top: none;
        }
        
        .totals-row td {
            border-top: 1px solid var(--primary);
            border-bottom: 1px solid var(--primary);
            font-weight: bold;
            background-color: #eff6ff;
        }
        
        .footer-grid {
            width: 100%;
            border: 1px solid var(--primary);
            border-top: none;
        }
        .footer-grid td {
            border: 1px solid var(--primary);
            vertical-align: top;
        }
        
        .bank-details { padding: 5px; }
        .terms-box { padding: 5px; font-size: 10px; }
        
        .summary-table { width: 100%; border: none; }
        .summary-table td { border: none; border-bottom: 1px solid var(--primary); padding: 4px 5px; }
        .summary-table tr:last-child td { border-bottom: none; }
        
        .signature-box {
            text-align: center;
            padding: 10px;
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

<table class="wrapper-table">
    <tr style="height: 1%;">
        <td style="vertical-align: top;">
    <!-- HEADER -->
    <table class="header-table">
        <tr>
            <td style="width: 50%;">
                <div class="org-logo-text">{{ substr($organization->name, 0, 1) }}</div>
                <div style="overflow: hidden;">
                    <div class="org-name">{{ $organization->name }}</div>
                    <div>{{ $organization->address_line_1 }}</div>
                    @if($organization->address_line_2)<div>{{ $organization->address_line_2 }}</div>@endif
                    <div>{{ $organization->city }}, {{ $organization->state }} {{ $organization->pincode }}</div>
                </div>
            </td>
            <td style="width: 50%;" class="contact-info">
                @include('pdf.templates.partials.logo', ['fallbackClass' => 'strong'])
                @if($contact->phone ?? false)<div><strong>Phone :</strong> {{ $contact->phone }}</div>@endif
                @if($contact->email ?? false)<div><strong>Email :</strong> {{ $contact->email }}</div>@endif
                <div><strong>Website :</strong> &nbsp;</div>
            </td>
        </tr>
    </table>

    <!-- TITLE BAR -->
    <div class="invoice-title-bar">
        <div style="text-align: center;">{{ $title }}</div>
        <div class="right-text">ORIGINAL FOR RECIPIENT</div>
    </div>

    <!-- DETAILS GRID -->
    <table class="details-grid">
        <tr>
            <td style="width: 50%; padding: 0;">
                <div class="header-cell">Customer Detail</div>
                <table class="customer-table">
                    <tr>
                        <td>Name</td>
                        <td>{{ $contact->name }}</td>
                    </tr>
                    <tr>
                        <td>Address</td>
                        <td>
                            {{ $contact->billing_address_line_1 }}<br>
                            @if($contact->billing_address_line_2){{ $contact->billing_address_line_2 }}<br>@endif
                            {{ $contact->billing_city }}, {{ $contact->billing_state }} {{ $contact->billing_pincode }}
                        </td>
                    </tr>
                    <tr>
                        <td>Phone</td>
                        <td>{{ $contact->phone ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>GSTIN</td>
                        <td>{{ $contact->gst_number ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Place of Supply</td>
                        <td>{{ $contact->shipping_state ?? $contact->billing_state ?? '-' }}</td>
                    </tr>
                </table>
            </td>
            <td style="width: 50%; padding: 0;">
                <table class="invoice-info-table">
                    <tr>
                        <td style="width: 25%;">Invoice No.</td>
                        <td class="strong">{{ $invoice->invoice_number ?? 'DRAFT' }}</td>
                        <td style="width: 25%;">Invoice Date</td>
                        <td>{{ $invoice->invoice_date?->format('d-M-Y') }}</td>
                    </tr>
                    @if($invoice->due_date)
                    <tr>
                        <td>Due Date</td>
                        <td>{{ $invoice->due_date?->format('d-M-Y') }}</td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endif
                </table>
            </td>
        </tr>
    </table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: top;">

    <!-- ITEMS TABLE -->
    <table class="items-table" style="height: 100%;">
        <thead>
            <tr>
                <th style="width: 5%;">Sr. No.</th>
                <th style="text-align: left;">Name of Product / Service</th>
                @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                    @if(isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true)
                    <th style="width: 10%;">HSN/SAC</th>
                    @endif
                @endif
                @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                <th style="width: 10%;">Quantity</th>
                @endif
                @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                <th style="width: 10%;">Rate</th>
                @endif
                @if(isset($template->show_fields['discount']) ? $template->show_fields['discount'] : true)
                <th style="width: 10%;">Discount</th>
                @endif
                @if(isset($template->show_fields['tax_details']) ? $template->show_fields['tax_details'] : true)
                <th style="width: 10%;">Tax</th>
                @endif
                <th style="width: 15%; text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $sno = 1; @endphp
            @foreach($invoice->items as $item)
            <tr style="height: 1%;">
                <td class="text-center">{{ $sno++ }}</td>
                <td>
                    <span class="strong">{{ $item->product?->name ?? $item->item_name }}</span><br>
                    <span style="font-size: 9px; color: #555;">{{ $item->description }}</span>
                </td>
                @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                    @if(isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true)
                    <td class="text-center">{{ $item->hsn_code ?? '-' }}</td>
                    @endif
                @endif
                @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                <td class="text-center">{{ $item->quantity + 0 }} {{ $item->unit }}</td>
                @endif
                @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                @endif
                @if(isset($template->show_fields['discount']) ? $template->show_fields['discount'] : true)
                <td class="text-right">
                    @if($item->discount_amount > 0)
                        {{ number_format($item->discount_amount, 2) }}
                    @else
                        -
                    @endif
                </td>
                @endif
                @if(isset($template->show_fields['tax_details']) ? $template->show_fields['tax_details'] : true)
                <td class="text-right">
                    @if($item->tax_amount > 0)
                        {{ number_format($item->tax_amount, 2) }}<br>
                        <span style="font-size: 8px;">({{ $item->taxRate?->percentage }}%)</span>
                    @else
                        -
                    @endif
                </td>
                @endif
                <td class="text-right">{{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
            <!-- Spacer row -->
            <tr style="height: 100%;">
                <td style="height: 100%;"></td>
                <td></td>
                @if($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service')
                    @if(isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true)
                    <td></td>
                    @endif
                @endif
                @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                <td></td>
                @endif
                @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                <td></td>
                @endif
                @if(isset($template->show_fields['discount']) ? $template->show_fields['discount'] : true)
                <td></td>
                @endif
                @if(isset($template->show_fields['tax_details']) ? $template->show_fields['tax_details'] : true)
                <td></td>
                @endif
                <td></td>
            </tr>
            <tr class="totals-row">
                @php 
                    $colspan = 2;
                    if (($invoice->invoice_type->value === 'sales' || $invoice->invoice_type->value === 'service') && (isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true)) $colspan++;
                    if (isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true) $colspan++;
                    if (isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true) $colspan++;
                    if (isset($template->show_fields['discount']) ? $template->show_fields['discount'] : true) $colspan++;
                    if (isset($template->show_fields['tax_details']) ? $template->show_fields['tax_details'] : true) $colspan++;
                @endphp
                <td colspan="{{ $colspan }}" class="text-right">Total</td>
                <td class="text-right">{{ number_format($invoice->subtotal + $invoice->tax_total - $invoice->discount_total, 2) }}</td>
            </tr>
        </tbody>
    </table>
        </td>
    </tr>
    <tr style="height: 1%;">
        <td style="vertical-align: bottom;">
    <!-- FOOTER GRID -->
    <table class="footer-grid">
        <tr>
            <!-- LEFT COLUMN -->
            <td style="width: 60%; padding: 0;">
                <div class="header-cell">Total in words</div>
                <div style="padding: 10px; text-transform: uppercase;">
                    INR {{ number_format($invoice->grand_total, 2) }} ONLY
                </div>
                
                @if($invoice->payment_info)
                <div class="header-cell" style="border-top: 1px solid var(--primary);">Bank Details</div>
                <div class="bank-details" style="white-space: pre-wrap;">{{ $invoice->payment_info }}</div>
                @endif
                
                @if($invoice->terms_and_conditions)
                <div class="terms-box" style="border-top: 1px solid var(--primary); white-space: pre-wrap;">{{ $invoice->terms_and_conditions }}</div>
                @endif
                
                @if($template->footer_note)
                <div class="terms-box" style="border-top: 1px solid var(--primary);">{{ $template->footer_note }}</div>
                @endif
            </td>
            
            <!-- RIGHT COLUMN -->
            <td style="width: 40%; padding: 0;">
                <table class="summary-table">
                    @if($invoice->round_off != 0)
                    <tr>
                        <td class="strong">Round off Amount</td>
                        <td class="text-right">{{ number_format($invoice->round_off, 2) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td class="strong" style="font-size: 12px;">Total Amount</td>
                        <td class="text-right strong" style="font-size: 12px;">₹ {{ number_format($invoice->grand_total, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-right" style="font-size: 9px;">(E & O.E.)</td>
                    </tr>
                </table>
                
                <div class="signature-box" style="border-top: 1px solid var(--primary);">
                    <div style="font-size: 9px; margin-bottom: 5px;">Certified that the particulars given above are true and correct.</div>
                    <div class="strong">For {{ $organization->name }}</div>
                    @include('pdf.templates.partials.signature', ['align' => 'flex-end'])
                    <div style="font-size: 9px;">Authorised Signatory</div>
                </div>
            </td>
        </tr>
    </table>
        </td>
    </tr>
</table>
</body>
</html>

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
            font-family: '{{ $template->font_choice ?? 'Helvetica' }}', sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header-bg {
            background-color: {{ $template->color_primary ?? '#4F46E5' }};
            color: white;
            padding: 30px;
            margin-bottom: 30px;
        }
        
        .header-bg table td {
            vertical-align: top;
        }
        
        .title {
            font-size: 32px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: right;
            margin-bottom: 5px;
        }
        
        .org-details h2 {
            margin: 0 0 5px 0;
            font-size: 24px;
            color: white;
        }
        
        .org-details p, .invoice-meta p {
            margin: 0 0 3px 0;
            font-size: 13px;
            color: rgba(255,255,255,0.8);
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .addresses {
            margin-bottom: 30px;
            padding: 0 30px;
        }
        
        .addresses td {
            width: 50%;
            vertical-align: top;
        }
        
        .addresses h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: {{ $template->color_secondary ?? '#F59E0B' }};
            margin: 0 0 8px 0;
            border-bottom: 2px solid #eee;
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .contact-details p {
            margin: 0 0 3px 0;
            font-size: 13px;
            color: #555;
        }
        
        .items-table {
            margin-bottom: 30px;
            width: calc(100% - 60px);
            margin-left: 30px;
        }
        
        .items-table th {
            background-color: #f8f9fa;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            padding: 12px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            border-bottom: 2px solid {{ $template->color_primary ?? '#4F46E5' }};
        }
        
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .totals-container {
            width: calc(100% - 60px);
            margin-left: 30px;
        }
        
        .totals {
            width: 40%;
            margin-left: 60%;
        }
        
        .totals th {
            text-align: left;
            padding: 8px 10px;
            color: #666;
            font-size: 13px;
        }
        
        .totals td {
            text-align: right;
            padding: 8px 10px;
            font-size: 13px;
        }
        
        .grand-total {
            background-color: {{ $template->color_secondary ?? '#F59E0B' }};
            color: white;
        }
        
        .grand-total th, .grand-total td {
            font-size: 16px;
            font-weight: bold;
            color: white;
            border: none;
            padding: 12px 10px;
        }
        
        .footer {
            margin-top: 50px;
            padding: 30px;
            text-align: center;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    @include('pdf.templates.partials.watermark')
<table style="width: 100%; height: 100%; border: none; border-collapse: collapse;">
    <tr>
        <td style="vertical-align: top; border: none; padding: 0;">
    <div class="header-bg">
        <table>
            <tr>
                <td class="org-details" style="width: 50%;">
                    @include('pdf.templates.partials.logo', ['fallbackTag' => 'h2'])
                    <p>{{ $organization->address_line_1 }} {{ $organization->address_line_2 }}</p>
                    <p>{{ $organization->city }} {{ $organization->state }} {{ $organization->pincode }}</p>
                    <p>{{ $organization->country }}</p>
                    @if($organization->gst_number)
                        <p><strong>GSTIN:</strong> {{ $organization->gst_number }}</p>
                    @endif
                </td>
                <td class="invoice-meta" style="width: 50%;">
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
                    <div class="title">{{ $title }}</div>
                    <p style="color: white; font-weight: bold; font-size: 16px;">#{{ $invoice->invoice_number ?? 'DRAFT' }}</p>
                    <p>Date: {{ $invoice->invoice_date?->format('M d, Y') }}</p>
                    <p>Due: {{ $invoice->due_date?->format('M d, Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="addresses">
        <table>
            <tr>
                <td class="contact-details">
                    <h3>Billed To</h3>
                    <p style="font-weight: bold; font-size: 15px; color: #111;">{{ $contact->name }}</p>
                    <p>{{ $contact->billing_address_line_1 }} {{ $contact->billing_address_line_2 }}</p>
                    <p>{{ $contact->billing_city }} {{ $contact->billing_state }} {{ $contact->billing_pincode }}</p>
                    <p>{{ $contact->billing_country }}</p>
                    @if($contact->gst_number)
                        <p><strong>GSTIN:</strong> {{ $contact->gst_number }}</p>
                    @endif
                </td>
                <td class="contact-details">
                    @if(isset($template->show_fields['shipping_address']) ? $template->show_fields['shipping_address'] : true)
                    <h3>Shipped To</h3>
                    <p style="font-weight: bold; font-size: 15px; color: #111;">{{ $contact->name }}</p>
                    <p>{{ $contact->shipping_address_line_1 }} {{ $contact->shipping_address_line_2 }}</p>
                    <p>{{ $contact->shipping_city }} {{ $contact->shipping_state }} {{ $contact->shipping_pincode }}</p>
                    <p>{{ $contact->shipping_country }}</p>
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item & Description</th>
                @if((isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true))
                    <th class="text-center">HSN/SAC</th>
                @endif
                @if(isset($template->show_fields['quantity']) ? $template->show_fields['quantity'] : true)
                <th class="text-center">Qty</th>
                @endif
                @if(isset($template->show_fields['rate']) ? $template->show_fields['rate'] : true)
                <th class="text-right">Rate</th>
                @endif
                @if(isset($template->show_fields['discount']) ? $template->show_fields['discount'] : true)
                    <th class="text-right">Discount</th>
                @endif
                @if((isset($template->show_fields['tax_details']) ? $template->show_fields['tax_details'] : true) && (!$organization->gst_number || !$organization->is_composition_tax_payer))
                    <th class="text-right">Tax</th>
                @endif
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product?->name ?? $item->item_name }}</strong>
                    @if($item->description)
                        <br><span style="color: #888; font-size: 11px;">{{ $item->description }}</span>
                    @endif
                </td>
                @if((isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true))
                    <td class="text-center">{{ $item->hsn_code ?? '-' }}</td>
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
                        @if($item->discount_percent > 0)
                            <br><span style="font-size: 10px; color: #999;">({{ rtrim(rtrim($item->discount_percent, '0'), '.') }}%)</span>
                        @endif
                    @else
                        -
                    @endif
                </td>
                @endif
                @if((isset($template->show_fields['tax_details']) ? $template->show_fields['tax_details'] : true) && (!$organization->gst_number || !$organization->is_composition_tax_payer))
                <td class="text-right">
                    @if($item->tax_amount > 0)
                        {{ number_format($item->tax_amount, 2) }}
                        <br><span style="font-size: 10px; color: #aaa;">({{ rtrim(rtrim($item->taxRate?->percentage, '0'), '.') }}%)</span>
                    @else
                        -
                    @endif
                </td>
                @endif
                <td class="text-right">{{ number_format($item->line_total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals-container">
        <table class="totals">
            <tr>
                <th>Subtotal</th>
                <td>{{ number_format($invoice->subtotal, 2) }}</td>
            </tr>
            @if($invoice->discount_total > 0)
            <tr>
                <th>Discount</th>
                <td style="color: #ef4444;">-{{ number_format($invoice->discount_total, 2) }}</td>
            </tr>
            @endif
            @if($invoice->tax_total > 0)
            <tr>
                <th>Total Tax</th>
                <td>{{ number_format($invoice->tax_total, 2) }}</td>
            </tr>
            @endif
            @if($invoice->round_off != 0)
            <tr>
                <th>Round Off</th>
                <td>{{ number_format($invoice->round_off, 2) }}</td>
            </tr>
            @endif
            <tr class="grand-total">
                <th>Grand Total (₹)</th>
                <td>{{ number_format($invoice->grand_total, 2) }}</td>
            </tr>
        </table>
    </div>
    
    @if($invoice->status->value !== 'draft')
        <div style="clear: both; margin-top: 50px; padding: 0 30px;">
            <p style="color: #666;"><strong>Amount in Words:</strong> <br> 
                Rupees {{ number_format($invoice->grand_total, 2) }} Only.
            </p>
        </div>
    @endif

    <table style="width: 100%; margin-top: 40px; border: none; padding: 0 30px;">
        <tr>
            <td style="width: 65%; vertical-align: top; border: none; padding: 0;">
                @if($invoice->payment_info)
                    <div style="margin-bottom: 20px;">
                        <h4 style="margin: 0 0 5px 0; font-size: 13px; color: #666; text-transform: uppercase;">Payment Information</h4>
                        <p style="margin: 0; font-size: 12px; color: #333; white-space: pre-wrap;">{{ $invoice->payment_info }}</p>
                    </div>
                @endif

                @if($invoice->terms_and_conditions)
                    <div>
                        <h4 style="margin: 0 0 5px 0; font-size: 13px; color: #666; text-transform: uppercase;">Terms & Conditions</h4>
                        <p style="margin: 0; font-size: 12px; color: #333; white-space: pre-wrap;">{{ $invoice->terms_and_conditions }}</p>
                    </div>
                @endif
            </td>
            <td style="width: 35%; vertical-align: bottom; text-align: center; border: none; padding: 0;">
                @include('pdf.templates.partials.signature', ['align' => 'center'])
                <div style="margin-top: 5px; padding-top: 5px; border-top: 1px solid #ccc; display: inline-block; min-width: 150px; font-weight: bold;">
                    Authorised Signatory
                </div>
            </td>
        </tr>
    </table>
        </td>
    </tr>
    <tr>
        <td style="vertical-align: bottom; border: none; padding: 0;">
    <div class="footer">
        @if($template->footer_note)
            <p>{{ $template->footer_note }}</p>
        @else
            <p>Thank you for your business!</p>
        @endif
        <p>This is a computer generated invoice and requires no signature.</p>
    </div>
        </td>
    </tr>
</table>
</body>
</html>

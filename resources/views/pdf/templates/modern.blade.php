<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? 'Draft' }}</title>
    <style>
        body {
            font-family: '{{ $template->font_choice ?? 'Helvetica' }}', sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
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
    <div class="header-bg">
        <table>
            <tr>
                <td class="org-details" style="width: 50%;">
                    <h2>{{ $organization->name }}</h2>
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
                <th class="text-center">Qty</th>
                <th class="text-right">Rate</th>
                @if((isset($template->show_fields['tax']) ? $template->show_fields['tax'] : true) && (!$organization->gst_number || !$organization->is_composition_tax_payer))
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
                <td class="text-center">{{ $item->quantity + 0 }} {{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                @if((isset($template->show_fields['tax']) ? $template->show_fields['tax'] : true) && (!$organization->gst_number || !$organization->is_composition_tax_payer))
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

    <div class="footer">
        @if($template->footer_note)
            <p>{{ $template->footer_note }}</p>
        @else
            <p>Thank you for your business!</p>
        @endif
        <p>This is a computer generated invoice and requires no signature.</p>
    </div>
</body>
</html>

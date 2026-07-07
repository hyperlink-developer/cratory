<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? 'Draft' }}</title>
    <style>
        body {
            font-family: '{{ $template->font_choice ?? 'Times-Roman' }}', serif;
            color: #333;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            padding: 40px;
            background-color: #ffffff;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header {
            margin-bottom: 40px;
            text-align: center;
            border-bottom: 2px solid {{ $template->color_primary ?? '#4F46E5' }};
            padding-bottom: 20px;
        }
        
        .org-details h2 {
            margin: 0 0 10px 0;
            font-size: 28px;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .org-details p {
            margin: 0 0 4px 0;
            font-size: 13px;
            color: #555;
        }
        
        .invoice-meta-container {
            margin-bottom: 40px;
        }
        
        .invoice-meta-container td {
            vertical-align: top;
        }
        
        .title {
            font-size: 20px;
            font-weight: normal;
            text-transform: uppercase;
            letter-spacing: 4px;
            color: {{ $template->color_secondary ?? '#F59E0B' }};
            margin-bottom: 15px;
        }
        
        .invoice-meta p {
            margin: 0 0 5px 0;
            font-size: 13px;
        }
        
        .addresses td {
            width: 50%;
            vertical-align: top;
        }
        
        .addresses h3 {
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            margin: 0 0 10px 0;
            font-weight: normal;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .contact-details p {
            margin: 0 0 4px 0;
            font-size: 13px;
        }
        
        .items-table {
            margin-top: 40px;
            margin-bottom: 40px;
        }
        
        .items-table th {
            padding: 12px 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            border-bottom: 1px solid {{ $template->color_primary ?? '#4F46E5' }};
            border-top: 1px solid {{ $template->color_primary ?? '#4F46E5' }};
        }
        
        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .totals {
            width: 45%;
            margin-left: 55%;
        }
        
        .totals th {
            text-align: left;
            padding: 8px 10px;
            font-size: 13px;
            color: #555;
            font-weight: normal;
        }
        
        .totals td {
            text-align: right;
            padding: 8px 10px;
            font-size: 13px;
        }
        
        .grand-total th, .grand-total td {
            font-size: 16px;
            font-weight: bold;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            border-top: 2px solid {{ $template->color_secondary ?? '#F59E0B' }};
            padding-top: 15px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 60px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="org-details">
            <h2>{{ $organization->name }}</h2>
            <p>{{ $organization->address_line_1 }} {{ $organization->address_line_2 }}</p>
            <p>{{ $organization->city }} {{ $organization->state }} {{ $organization->pincode }} &bull; {{ $organization->country }}</p>
            @if($organization->gst_number || $organization->pan_number)
                <p>
                    @if($organization->gst_number) GSTIN: {{ $organization->gst_number }} @endif
                    @if($organization->gst_number && $organization->pan_number) &bull; @endif
                    @if($organization->pan_number) PAN: {{ $organization->pan_number }} @endif
                </p>
            @endif
        </div>
    </div>

    <table class="invoice-meta-container">
        <tr>
            <td style="width: 50%;">
                @php
                    $title = ucfirst(strtolower($invoice->invoice_type->value)) . ' Invoice';
                    if ($organization->gst_number) {
                        if ($organization->is_composition_tax_payer) {
                            $title = 'Bill of Supply';
                        } else {
                            $title = $invoice->invoice_type->value === 'sales' ? 'Tax Invoice' : 'Service Invoice';
                        }
                    } else {
                        $title = 'Invoice';
                    }
                @endphp
                <div class="title">{{ $title }}</div>
            </td>
            <td class="invoice-meta" style="width: 50%; text-align: right;">
                <p><strong>Invoice No:</strong> {{ $invoice->invoice_number ?? 'DRAFT' }}</p>
                <p><strong>Date:</strong> {{ $invoice->invoice_date?->format('F d, Y') }}</p>
                <p><strong>Due Date:</strong> {{ $invoice->due_date?->format('F d, Y') }}</p>
            </td>
        </tr>
    </table>

    <table class="addresses">
        <tr>
            <td class="contact-details">
                <h3>Billed To</h3>
                <p style="font-size: 15px; color: {{ $template->color_primary ?? '#4F46E5' }};">{{ $contact->name }}</p>
                <p>{{ $contact->billing_address_line_1 }} {{ $contact->billing_address_line_2 }}</p>
                <p>{{ $contact->billing_city }} {{ $contact->billing_state }} {{ $contact->billing_pincode }}</p>
                <p>{{ $contact->billing_country }}</p>
                @if($contact->gst_number)
                    <p>GSTIN: {{ $contact->gst_number }}</p>
                @endif
            </td>
            <td class="contact-details">
                @if(isset($template->show_fields['shipping_address']) ? $template->show_fields['shipping_address'] : true)
                <h3>Shipped To</h3>
                <p style="font-size: 15px; color: {{ $template->color_primary ?? '#4F46E5' }};">{{ $contact->name }}</p>
                <p>{{ $contact->shipping_address_line_1 }} {{ $contact->shipping_address_line_2 }}</p>
                <p>{{ $contact->shipping_city }} {{ $contact->shipping_state }} {{ $contact->shipping_pincode }}</p>
                <p>{{ $contact->shipping_country }}</p>
                @endif
            </td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item & Description</th>
                @if((isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true))
                    <th class="text-center">HSN/SAC</th>
                @endif
                <th class="text-center">Qty</th>
                <th class="text-right">Rate</th>
                @if(isset($template->show_fields['discount']) ? $template->show_fields['discount'] : true)
                    <th class="text-right">Discount</th>
                @endif
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
                    <strong style="font-weight: normal; color: {{ $template->color_primary ?? '#4F46E5' }};">{{ $item->product?->name ?? $item->item_name }}</strong>
                    @if($item->description)
                        <br><span style="color: #888; font-size: 12px; font-style: italic;">{{ $item->description }}</span>
                    @endif
                </td>
                @if((isset($template->show_fields['hsn']) ? $template->show_fields['hsn'] : true))
                    <td class="text-center">{{ $item->hsn_code ?? '-' }}</td>
                @endif
                <td class="text-center">{{ $item->quantity + 0 }} {{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->rate, 2) }}</td>
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
    
    @if($invoice->status->value !== 'draft')
        <div style="clear: both; margin-top: 50px;">
            <p style="color: #666; font-style: italic;">Amount in words: Rupees {{ number_format($invoice->grand_total, 2) }} Only.</p>
        </div>
    @endif

    <div style="clear: both; margin-top: 40px;">
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
    </div>

    <div class="footer">
        @if($template->footer_note)
            <p>{{ $template->footer_note }}</p>
        @else
            <p>Thank you for your business.</p>
        @endif
    </div>
</body>
</html>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? 'Draft' }}</title>
    <style>
        body {
            font-family: '{{ $template->font_choice ?? 'Helvetica' }}', sans-serif;
            color: #222;
            font-size: 13px;
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
            margin-bottom: 50px;
        }
        
        .header table td {
            vertical-align: top;
        }
        
        .title {
            font-size: 24px;
            text-transform: uppercase;
            letter-spacing: 3px;
            text-align: right;
            margin-bottom: 20px;
            color: {{ $template->color_primary ?? '#000' }};
        }
        
        .org-details h2 {
            margin: 0 0 10px 0;
            font-size: 16px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .org-details p, .invoice-meta p {
            margin: 0 0 5px 0;
            color: #555;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .addresses {
            margin-bottom: 40px;
        }
        
        .addresses td {
            width: 50%;
            vertical-align: top;
        }
        
        .addresses h3 {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #888;
            margin: 0 0 10px 0;
        }
        
        .contact-details p {
            margin: 0 0 5px 0;
            color: #333;
        }
        
        .items-table {
            margin-bottom: 40px;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        
        .items-table th {
            padding: 15px 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #000;
            border-bottom: 1px solid #ccc;
        }
        
        .items-table td {
            padding: 15px 10px;
            border-bottom: 1px solid #eee;
        }
        
        .items-table tr:last-child td {
            border-bottom: none;
        }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .totals {
            width: 40%;
            margin-left: 60%;
        }
        
        .totals th {
            text-align: left;
            padding: 10px;
            font-size: 12px;
            color: #555;
        }
        
        .totals td {
            text-align: right;
            padding: 10px;
            font-size: 13px;
        }
        
        .grand-total th, .grand-total td {
            font-size: 14px;
            font-weight: bold;
            color: #000;
            border-top: 1px solid #000;
            border-bottom: 1px double #000;
        }
        
        .footer {
            margin-top: 80px;
            text-align: center;
            font-size: 11px;
            color: #999;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table>
            <tr>
                <td class="org-details" style="width: 50%;">
                    <h2>{{ $organization->name }}</h2>
                    <p>{{ $organization->address_line_1 }} {{ $organization->address_line_2 }}</p>
                    <p>{{ $organization->city }} {{ $organization->state }} {{ $organization->pincode }}</p>
                    <p>{{ $organization->country }}</p>
                    @if($organization->gst_number)
                        <p>GSTIN: {{ $organization->gst_number }}</p>
                    @endif
                </td>
                <td class="invoice-meta" style="width: 50%;">
                    @php
                        $title = strtolower($invoice->invoice_type->value) . ' invoice';
                        if ($organization->gst_number) {
                            if ($organization->is_composition_tax_payer) {
                                $title = 'bill of supply';
                            } else {
                                $title = $invoice->invoice_type->value === 'sales' ? 'tax invoice' : 'service invoice';
                            }
                        } else {
                            $title = 'invoice';
                        }
                    @endphp
                    <div class="title">{{ $title }}</div>
                    <p>No. {{ $invoice->invoice_number ?? 'DRAFT' }}</p>
                    <p>Date: {{ $invoice->invoice_date?->format('d/m/Y') }}</p>
                    <p>Due: {{ $invoice->due_date?->format('d/m/Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="addresses">
        <table>
            <tr>
                <td class="contact-details">
                    <h3>Bill To</h3>
                    <p style="font-weight: bold;">{{ $contact->name }}</p>
                    <p>{{ $contact->billing_address_line_1 }} {{ $contact->billing_address_line_2 }}</p>
                    <p>{{ $contact->billing_city }} {{ $contact->billing_state }} {{ $contact->billing_pincode }}</p>
                    <p>{{ $contact->billing_country }}</p>
                    @if($contact->gst_number)
                        <p>GSTIN: {{ $contact->gst_number }}</p>
                    @endif
                </td>
                <td class="contact-details">
                    @if(isset($template->show_fields['shipping_address']) ? $template->show_fields['shipping_address'] : true)
                    <h3>Ship To</h3>
                    <p style="font-weight: bold;">{{ $contact->name }}</p>
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
                <th>Description</th>
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
                    <strong>{{ $item->product?->name ?? $item->item_name }}</strong>
                    @if($item->description)
                        <br><span style="color: #777; font-size: 11px;">{{ $item->description }}</span>
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
            <th>Tax</th>
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
            <th>Total (₹)</th>
            <td>{{ number_format($invoice->grand_total, 2) }}</td>
        </tr>
    </table>
    
    @if($invoice->status->value !== 'draft')
        <div style="clear: both; margin-top: 60px;">
            <p style="color: #777; font-size: 12px;">Amount in words: Rupees {{ number_format($invoice->grand_total, 2) }} Only.</p>
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
            <p>Thank you.</p>
        @endif
    </div>
</body>
</html>

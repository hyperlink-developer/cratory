<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number ?? 'Draft' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #333;
            font-size: 14px;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        
        /* Using inline styles and standard css for DomPDF support */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .header {
            margin-bottom: 30px;
        }
        
        .header table td {
            vertical-align: top;
        }
        
        .title {
            font-size: 28px;
            font-weight: bold;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: right;
            margin-bottom: 5px;
        }
        
        .org-details h2 {
            margin: 0 0 5px 0;
            font-size: 20px;
            color: #111;
        }
        
        .org-details p, .contact-details p {
            margin: 0 0 3px 0;
            font-size: 12px;
            color: #666;
        }
        
        .invoice-meta {
            text-align: right;
        }
        
        .invoice-meta p {
            margin: 0 0 3px 0;
            font-size: 13px;
        }
        
        .divider {
            border-top: 2px solid {{ $template->color_primary ?? '#4F46E5' }};
            margin: 20px 0;
        }
        
        .addresses {
            margin-bottom: 30px;
        }
        
        .addresses td {
            width: 50%;
            vertical-align: top;
        }
        
        .addresses h3 {
            font-size: 14px;
            text-transform: uppercase;
            color: #999;
            margin: 0 0 8px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            display: inline-block;
        }
        
        .items-table {
            margin-bottom: 30px;
        }
        
        .items-table th {
            background-color: {{ $template->color_primary ?? '#4F46E5' }};
            color: #fff;
            padding: 10px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .items-table td {
            padding: 10px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }
        
        .text-right { text-align: right !important; }
        .text-center { text-align: center !important; }
        
        .totals {
            width: 50%;
            margin-left: 50%;
        }
        
        .totals th {
            text-align: left;
            padding: 6px 10px;
            color: #666;
            font-size: 13px;
        }
        
        .totals td {
            text-align: right;
            padding: 6px 10px;
            font-size: 13px;
        }
        
        .grand-total th, .grand-total td {
            font-size: 16px;
            font-weight: bold;
            color: {{ $template->color_primary ?? '#4F46E5' }};
            border-top: 2px solid #eee;
            padding-top: 10px;
            margin-top: 10px;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #999;
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
                        <p><strong>GSTIN:</strong> {{ $organization->gst_number }}</p>
                    @endif
                    @if($organization->pan_number)
                        <p><strong>PAN:</strong> {{ $organization->pan_number }}</p>
                    @endif
                </td>
                <td class="invoice-meta" style="width: 50%;">
                    <div class="title">{{ strtoupper($invoice->invoice_type->value) }} INVOICE</div>
                    <p><strong>Invoice No:</strong> {{ $invoice->invoice_number ?? 'DRAFT' }}</p>
                    <p><strong>Date:</strong> {{ $invoice->invoice_date?->format('d M, Y') }}</p>
                    <p><strong>Due Date:</strong> {{ $invoice->due_date?->format('d M, Y') }}</p>
                </td>
            </tr>
        </table>
    </div>

    <div class="divider"></div>

    <div class="addresses">
        <table>
            <tr>
                <td class="contact-details">
                    <h3>Billed To</h3>
                    <p style="font-weight: bold; font-size: 14px; color: #333;">{{ $contact->name }}</p>
                    <p>{{ $contact->billing_address_line_1 }} {{ $contact->billing_address_line_2 }}</p>
                    <p>{{ $contact->billing_city }} {{ $contact->billing_state }} {{ $contact->billing_pincode }}</p>
                    <p>{{ $contact->billing_country }}</p>
                    @if($contact->gst_number)
                        <p><strong>GSTIN:</strong> {{ $contact->gst_number }}</p>
                    @endif
                </td>
                <td class="contact-details">
                    @if($template->show_fields['shipping_address'] ?? true)
                    <h3>Shipped To</h3>
                    <p style="font-weight: bold; font-size: 14px; color: #333;">{{ $contact->name }}</p>
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
                @if($invoice->invoice_type->value === 'sales' && ($template->show_fields['hsn'] ?? true))
                    <th class="text-center">HSN</th>
                @elseif($invoice->invoice_type->value === 'service' && ($template->show_fields['hsn'] ?? true))
                    <th class="text-center">SAC</th>
                @endif
                <th class="text-center">Qty</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->product?->name ?? $item->item_name }}</strong>
                    @if($item->description)
                        <br><span style="color: #666; font-size: 11px;">{{ $item->description }}</span>
                    @endif
                </td>
                @if($invoice->invoice_type->value === 'sales' && ($template->show_fields['hsn'] ?? true))
                    <td class="text-center">{{ $item->product?->hsn_code ?? '-' }}</td>
                @elseif($invoice->invoice_type->value === 'service' && ($template->show_fields['hsn'] ?? true))
                    <td class="text-center">{{ $item->product?->sac_code ?? '-' }}</td>
                @endif
                <td class="text-center">{{ $item->quantity + 0 }} {{ $item->unit }}</td>
                <td class="text-right">{{ number_format($item->rate, 2) }}</td>
                <td class="text-right">
                    @if($item->tax_amount > 0)
                        {{ number_format($item->tax_amount, 2) }}
                        <br><span style="font-size: 10px; color: #999;">({{ rtrim(rtrim($item->taxRate?->percentage, '0'), '.') }}%)</span>
                    @else
                        -
                    @endif
                </td>
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
        <div style="clear: both; margin-top: 40px;">
            <p><strong>Amount in Words:</strong> <br> 
                <!-- Simple placeholder for amount in words -->
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

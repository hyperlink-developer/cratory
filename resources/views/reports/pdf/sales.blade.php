<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .summary { margin-top: 20px; border: 1px solid #ddd; padding: 15px; background-color: #f8f9fa; }
        .summary p { margin: 5px 0; font-size: 14px; }
        .summary strong { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Sales Report</h1>
        <p>{{ $org->name }}</p>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <p>Total Sales (Inc. Tax): <strong>{{ number_format($totalSales, 2) }}</strong></p>
        <p>Total Tax Collected: <strong>{{ number_format($totalTax, 2) }}</strong></p>
        <p>Total Invoices: <strong>{{ count($sales) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Invoice #</th>
                <th>Customer</th>
                <th>Status</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
                <tr>
                    <td>{{ $sale->invoice_date->format('M d, Y') }}</td>
                    <td>{{ $sale->invoice_number }}</td>
                    <td>{{ $sale->contact?->display_name }}</td>
                    <td>{{ ucfirst($sale->status?->value ?? '') }}</td>
                    <td class="text-right">{{ number_format($sale->subtotal, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->tax_total, 2) }}</td>
                    <td class="text-right">{{ number_format($sale->grand_total, 2) }}</td>
                </tr>
            @endforeach
            @if(count($sales) === 0)
                <tr>
                    <td colspan="7" style="text-align: center;">No sales found for the selected period.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>

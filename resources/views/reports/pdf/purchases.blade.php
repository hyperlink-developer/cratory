<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Report</title>
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
        <h1>Purchase Report</h1>
        <p>{{ $org->name }}</p>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    </div>

    <div class="summary">
        <p>Total Purchases (Inc. Tax): <strong>{{ number_format($totalPurchases, 2) }}</strong></p>
        <p>Total Tax Paid: <strong>{{ number_format($totalTax, 2) }}</strong></p>
        <p>Total Bills: <strong>{{ count($purchases) }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Bill #</th>
                <th>Vendor</th>
                <th>Status</th>
                <th class="text-right">Subtotal</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($purchases as $purchase)
                <tr>
                    <td>{{ $purchase->purchase_date->format('M d, Y') }}</td>
                    <td>{{ $purchase->bill_number ?? $purchase->purchase_number }}</td>
                    <td>{{ $purchase->contact?->display_name }}</td>
                    <td>{{ ucfirst($purchase->status?->value ?? '') }}</td>
                    <td class="text-right">{{ number_format($purchase->subtotal, 2) }}</td>
                    <td class="text-right">{{ number_format($purchase->tax_total, 2) }}</td>
                    <td class="text-right">{{ number_format($purchase->grand_total, 2) }}</td>
                </tr>
            @endforeach
            @if(count($purchases) === 0)
                <tr>
                    <td colspan="7" style="text-align: center;">No purchases found for the selected period.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>

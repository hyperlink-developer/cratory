<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trial Balance</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 12px; color: #333; }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; font-weight: bold; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .bg-light { background-color: #f8f9fa; }
        .text-muted { color: #6c757d; font-size: 10px; text-transform: capitalize; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .warning { background-color: #fef2f2; color: #dc2626; padding: 10px; border: 1px solid #fecaca; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Trial Balance</h1>
        <p>{{ $org->name }}</p>
        <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}</p>
    </div>

    @if(!$reportData['is_balanced'])
    <div class="warning">
        <strong>Warning: Trial Balance Mismatch!</strong><br>
        Total Debits and Credits do not match. Difference: {{ number_format(abs($reportData['total_debit'] - $reportData['total_credit']), 2) }}.
    </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>Account</th>
                <th class="text-right">Debit</th>
                <th class="text-right">Credit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['lines'] as $line)
                <tr>
                    <td>
                        {{ $line['account_code'] ? $line['account_code'] . ' - ' : '' }}{{ $line['account_name'] }}<br>
                        <span class="text-muted">{{ $line['type'] }}</span>
                    </td>
                    <td class="text-right">{{ $line['debit'] > 0 ? number_format($line['debit'], 2) : '-' }}</td>
                    <td class="text-right">{{ $line['credit'] > 0 ? number_format($line['credit'], 2) : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" style="text-align: center;">No ledger activity found as of this date.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr class="bg-light font-bold">
                <td class="text-right">TOTALS</td>
                <td class="text-right {{ $reportData['is_balanced'] ? 'text-green' : 'text-red' }}">{{ number_format($reportData['total_debit'], 2) }}</td>
                <td class="text-right {{ $reportData['is_balanced'] ? 'text-green' : 'text-red' }}">{{ number_format($reportData['total_credit'], 2) }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>

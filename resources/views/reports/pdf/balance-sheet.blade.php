<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Balance Sheet</title>
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
        .indent { padding-left: 30px; color: #555; }
        .group-header { font-weight: bold; background-color: #f1f3f5; }
        .section-total { font-weight: bold; text-align: right; }
        .final-total { font-size: 14px; font-weight: bold; background-color: #e9ecef; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
        .warning { background-color: #fef2f2; color: #dc2626; padding: 10px; border: 1px solid #fecaca; margin-bottom: 15px; border-radius: 4px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Balance Sheet</h1>
        <p>{{ $org->name }}</p>
        <p>As of {{ \Carbon\Carbon::parse($asOfDate)->format('M d, Y') }}</p>
    </div>

    @if(!$reportData['is_balanced'])
    <div class="warning">
        <strong>Warning: Balance Sheet Mismatch!</strong><br>
        Total Assets do not equal Total Liabilities + Equity. Difference: {{ number_format(abs($reportData['total_assets'] - $reportData['total_liabilities_and_equity']), 2) }}.
    </div>
    @endif

    <table>
        <!-- ASSETS -->
        <thead>
            <tr>
                <th colspan="2">Assets</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['assets'] as $group)
                <tr>
                    <td colspan="2" class="group-header">{{ $group['group'] }}</td>
                </tr>
                @foreach($group['accounts'] as $account)
                <tr>
                    <td class="indent">{{ $account['code'] ? $account['code'] . ' - ' : '' }}{{ $account['name'] }}</td>
                    <td class="text-right">{{ number_format($account['balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="section-total">Total {{ $group['group'] }}</td>
                    <td class="section-total">{{ number_format($group['total'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No assets recorded.</td>
                </tr>
            @endforelse
            <tr class="bg-light">
                <td class="font-bold">Total Assets</td>
                <td class="font-bold text-right text-green">{{ number_format($reportData['total_assets'], 2) }}</td>
            </tr>

            <!-- LIABILITIES -->
            <tr>
                <th colspan="2" style="padding-top: 20px;">Liabilities</th>
            </tr>
            @forelse($reportData['liabilities'] as $group)
                <tr>
                    <td colspan="2" class="group-header">{{ $group['group'] }}</td>
                </tr>
                @foreach($group['accounts'] as $account)
                <tr>
                    <td class="indent">{{ $account['code'] ? $account['code'] . ' - ' : '' }}{{ $account['name'] }}</td>
                    <td class="text-right">{{ number_format($account['balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="section-total">Total {{ $group['group'] }}</td>
                    <td class="section-total">{{ number_format($group['total'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No liabilities recorded.</td>
                </tr>
            @endforelse
            <tr class="bg-light">
                <td class="font-bold">Total Liabilities</td>
                <td class="font-bold text-right">{{ number_format($reportData['total_liabilities'], 2) }}</td>
            </tr>

            <!-- EQUITY -->
            <tr>
                <th colspan="2" style="padding-top: 20px;">Equity</th>
            </tr>
            @forelse($reportData['equity'] as $group)
                <tr>
                    <td colspan="2" class="group-header">{{ $group['group'] }}</td>
                </tr>
                @foreach($group['accounts'] as $account)
                <tr>
                    <td class="indent">{{ $account['code'] ? $account['code'] . ' - ' : '' }}{{ $account['name'] }}</td>
                    <td class="text-right">{{ number_format($account['balance'], 2) }}</td>
                </tr>
                @endforeach
                <tr>
                    <td class="section-total">Total {{ $group['group'] }}</td>
                    <td class="section-total">{{ number_format($group['total'], 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" style="text-align: center;">No equity recorded.</td>
                </tr>
            @endforelse
            <tr class="bg-light">
                <td class="font-bold">Total Equity</td>
                <td class="font-bold text-right">{{ number_format($reportData['total_equity'], 2) }}</td>
            </tr>

            <!-- TOTAL LIABILITIES & EQUITY -->
            <tr class="final-total">
                <td style="padding: 15px 8px;">TOTAL LIABILITIES & EQUITY</td>
                <td class="text-right {{ $reportData['is_balanced'] ? 'text-green' : 'text-red' }}" style="padding: 15px 8px;">
                    {{ number_format($reportData['total_liabilities_and_equity'], 2) }}
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profit and Loss</title>
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
        .net-profit { font-size: 16px; font-weight: bold; background-color: #e9ecef; }
        .text-green { color: #16a34a; }
        .text-red { color: #dc2626; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Profit & Loss Statement</h1>
        <p>{{ $org->name }}</p>
        <p>Period: {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}</p>
    </div>

    @if($layout === 'linear')
    <table>
        <!-- REVENUE -->
        <thead>
            <tr>
                <th colspan="2">Operating Revenue</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reportData['revenue'] as $group)
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
                    <td colspan="2" style="text-align: center;">No revenue recorded.</td>
                </tr>
            @endforelse
            <tr class="bg-light">
                <td class="font-bold">Total Operating Revenue</td>
                <td class="font-bold text-right text-green">{{ number_format($reportData['total_revenue'], 2) }}</td>
            </tr>

            <!-- EXPENSES -->
            <tr>
                <th colspan="2" style="padding-top: 20px;">Operating Expenses</th>
            </tr>
            @forelse($reportData['expenses'] as $group)
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
                    <td colspan="2" style="text-align: center;">No expenses recorded.</td>
                </tr>
            @endforelse
            <tr class="bg-light">
                <td class="font-bold">Total Operating Expenses</td>
                <td class="font-bold text-right text-red">{{ number_format($reportData['total_expenses'], 2) }}</td>
            </tr>

            <!-- NET PROFIT -->
            <tr class="net-profit">
                <td style="padding: 15px 8px;">NET PROFIT / (LOSS)</td>
                <td class="text-right {{ $reportData['net_profit'] >= 0 ? 'text-green' : 'text-red' }}" style="padding: 15px 8px;">
                    {{ number_format($reportData['net_profit'], 2) }}
                </td>
            </tr>
        </tbody>
    </table>
    @else
    <table style="border: none; margin-top: 0;">
        <tr>
            <!-- LEFT (EXPENSES) -->
            <td style="width: 50%; vertical-align: top; padding: 0; border: none; border-right: 1px solid #ddd;">
                <table style="margin-top: 0;">
                    <thead>
                        <tr><th colspan="2">Particulars (Expenses)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($reportData['expenses'] as $group)
                            <tr><td colspan="2" class="group-header">{{ $group['group'] }}</td></tr>
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
                            <tr><td colspan="2" style="text-align: center;">No expenses recorded.</td></tr>
                        @endforelse

                        @if($reportData['net_profit'] >= 0)
                            <tr>
                                <td class="font-bold" style="padding-top: 20px;">Net Profit</td>
                                <td class="font-bold text-right text-green" style="padding-top: 20px;">{{ number_format($reportData['net_profit'], 2) }}</td>
                            </tr>
                        @endif
                        <tr class="bg-light">
                            <td class="font-bold" style="padding: 15px 8px;">TOTAL</td>
                            @php $expenseSideTotal = $reportData['total_expenses'] + ($reportData['net_profit'] >= 0 ? $reportData['net_profit'] : 0); @endphp
                            <td class="font-bold text-right" style="padding: 15px 8px;">{{ number_format($expenseSideTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>

            <!-- RIGHT (REVENUE) -->
            <td style="width: 50%; vertical-align: top; padding: 0; border: none;">
                <table style="margin-top: 0; border-left: none;">
                    <thead>
                        <tr><th colspan="2" style="border-left: none;">Particulars (Revenue)</th></tr>
                    </thead>
                    <tbody>
                        @forelse($reportData['revenue'] as $group)
                            <tr><td colspan="2" class="group-header" style="border-left: none;">{{ $group['group'] }}</td></tr>
                            @foreach($group['accounts'] as $account)
                            <tr>
                                <td class="indent" style="border-left: none;">{{ $account['code'] ? $account['code'] . ' - ' : '' }}{{ $account['name'] }}</td>
                                <td class="text-right">{{ number_format($account['balance'], 2) }}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td class="section-total" style="border-left: none;">Total {{ $group['group'] }}</td>
                                <td class="section-total">{{ number_format($group['total'], 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="2" style="text-align: center; border-left: none;">No revenue recorded.</td></tr>
                        @endforelse

                        @if($reportData['net_profit'] < 0)
                            <tr>
                                <td class="font-bold" style="padding-top: 20px; border-left: none;">Net Loss</td>
                                <td class="font-bold text-right text-red" style="padding-top: 20px;">{{ number_format(abs($reportData['net_profit']), 2) }}</td>
                            </tr>
                        @endif
                        <tr class="bg-light">
                            <td class="font-bold" style="padding: 15px 8px; border-left: none;">TOTAL</td>
                            @php $revenueSideTotal = $reportData['total_revenue'] + ($reportData['net_profit'] < 0 ? abs($reportData['net_profit']) : 0); @endphp
                            <td class="font-bold text-right" style="padding: 15px 8px;">{{ number_format($revenueSideTotal, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    @endif
</body>
</html>

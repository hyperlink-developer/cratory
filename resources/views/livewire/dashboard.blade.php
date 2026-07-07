<div>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-text-primary">Dashboard</h1>
            <p class="text-sm text-text-secondary mt-1">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        
        <div class="flex bg-surface-lighter rounded-lg p-1 border border-white/5">
            <button wire:click="setPeriod('week')" class="px-4 py-1.5 text-xs font-medium rounded-md transition-colors {{ $period === 'week' ? 'bg-surface text-text-primary shadow-sm' : 'text-text-muted hover:text-text-secondary' }}">Week</button>
            <button wire:click="setPeriod('month')" class="px-4 py-1.5 text-xs font-medium rounded-md transition-colors {{ $period === 'month' ? 'bg-surface text-text-primary shadow-sm' : 'text-text-muted hover:text-text-secondary' }}">Month</button>
            <button wire:click="setPeriod('quarter')" class="px-4 py-1.5 text-xs font-medium rounded-md transition-colors {{ $period === 'quarter' ? 'bg-surface text-text-primary shadow-sm' : 'text-text-muted hover:text-text-secondary' }}">Quarter</button>
        </div>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-5 gap-6 mb-8">
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-text-secondary">Net Profit ({{ ucfirst($period) }})</h3>
                <div class="w-8 h-8 rounded-full bg-indigo-400/10 flex items-center justify-center text-indigo-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M3 14h18" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold {{ $kpis['net_profit'] >= 0 ? 'text-green-400' : 'text-red-400' }}">₹{{ number_format($kpis['net_profit'], 2) }}</p>
        </div>
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-green-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-text-secondary">Sales ({{ ucfirst($period) }})</h3>
                <div class="w-8 h-8 rounded-full bg-green-400/10 flex items-center justify-center text-green-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-text-primary">₹{{ number_format($kpis['total_sales'], 2) }}</p>
        </div>
        
        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-orange-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-text-secondary">Purchases ({{ ucfirst($period) }})</h3>
                <div class="w-8 h-8 rounded-full bg-orange-400/10 flex items-center justify-center text-orange-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-text-primary">₹{{ number_format($kpis['total_purchases'], 2) }}</p>
        </div>

        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-blue-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-text-secondary">To Collect</h3>
                <div class="w-8 h-8 rounded-full bg-blue-400/10 flex items-center justify-center text-blue-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-text-primary">₹{{ number_format($kpis['total_receivable'], 2) }}</p>
        </div>

        <div class="glass-card p-5 relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-purple-400/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-text-secondary">To Pay</h3>
                <div class="w-8 h-8 rounded-full bg-purple-400/10 flex items-center justify-center text-purple-400">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-text-primary">₹{{ number_format($kpis['total_payable'], 2) }}</p>
        </div>
    </div>

    @php
        $hasChartData = array_sum($chartData['sales']) > 0 || array_sum($chartData['purchases']) > 0;
    @endphp

    @if($hasChartData)
    <div class="glass-card p-5 mb-6">
        <h2 class="text-base font-semibold text-text-primary mb-4">Revenue vs Expenses (Last 6 Months)</h2>
        <div id="chart" 
             x-data="{ 
                init() {
                    let options = {
                        series: [{
                            name: 'Sales',
                            data: {{ json_encode($chartData['sales']) }}
                        }, {
                            name: 'Purchases',
                            data: {{ json_encode($chartData['purchases']) }}
                        }],
                        chart: {
                            type: 'area',
                            height: 300,
                            toolbar: { show: false },
                            background: 'transparent'
                        },
                        colors: ['#4ade80', '#fb923c'],
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                opacityFrom: 0.4,
                                opacityTo: 0.05,
                                stops: [0, 90, 100]
                            }
                        },
                        dataLabels: { enabled: false },
                        stroke: { curve: 'smooth', width: 2 },
                        xaxis: {
                            categories: {{ json_encode($chartData['categories']) }},
                            axisBorder: { show: false },
                            axisTicks: { show: false },
                            labels: {
                                style: { colors: '#94a3b8' }
                            }
                        },
                        yaxis: {
                            labels: {
                                style: { colors: '#94a3b8' },
                                formatter: function (val) {
                                    return '₹' + val.toLocaleString();
                                }
                            }
                        },
                        grid: {
                            borderColor: 'rgba(255,255,255,0.05)',
                            strokeDashArray: 4,
                        },
                        theme: { mode: 'dark' },
                        legend: { position: 'top', horizontalAlign: 'right' },
                        tooltip: { theme: 'dark' }
                    };

                    try {
                        let chart = new ApexCharts(this.$el, options);
                        chart.render();
                    } catch (error) {
                        console.error('ApexCharts Error:', error);
                        if(window.Toast) { window.Toast.fire({icon: 'error', title: 'Chart Error: ' + error.message}); }
                    }
                }
             }"
        ></div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Activity Feed -->
        <div class="lg:col-span-2 glass-card p-0 flex flex-col h-[400px]">
            <div class="p-5 border-b border-white/5">
                <h2 class="text-base font-semibold text-text-primary">Recent Activity</h2>
            </div>
            <div class="flex-1 overflow-y-auto p-2">
                @if($recentActivity->isEmpty())
                    <div class="p-8 text-center text-text-muted text-sm">No recent activity found.</div>
                @else
                    <ul class="space-y-1">
                        @foreach($recentActivity as $activity)
                            <li>
                                <a href="{{ $activity['route'] }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-white/5 transition-colors group" wire:navigate>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-surface flex items-center justify-center text-xs font-medium border border-white/5">
                                            @if($activity['type'] === 'invoice')
                                                <span class="text-blue-400">INV</span>
                                            @else
                                                <span class="text-green-400">REC</span>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-text-primary group-hover:text-accent transition-colors">{{ $activity['label'] }}</p>
                                            <p class="text-xs text-text-muted">{{ $activity['contact'] }} • {{ Carbon\Carbon::parse($activity['date'])->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-bold text-text-primary">₹{{ number_format($activity['amount'], 2) }}</p>
                                        <span class="text-[0.65rem] uppercase tracking-wider text-text-secondary">{{ $activity['status'] }}</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        <!-- Right Sidebar Widgets -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Overdue Invoices -->
            <div class="glass-card p-0 flex flex-col h-[190px]">
                <div class="p-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-text-primary">Overdue Invoices</h2>
                    <span class="text-xs font-medium text-red-400 bg-red-400/10 px-2 py-0.5 rounded">{{ count($overdueInvoices) }}</span>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    @forelse($overdueInvoices as $invoice)
                        <a href="{{ route('invoices.edit', $invoice->uuid) }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-white/5 transition-colors group" wire:navigate>
                            <div>
                                <p class="text-xs font-medium text-text-primary">{{ $invoice->contact->display_name }}</p>
                                <p class="text-[0.65rem] text-red-400">{{ Carbon\Carbon::parse($invoice->due_date)->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-bold text-text-primary">₹{{ number_format($invoice->balance_due, 2) }}</span>
                        </a>
                    @empty
                        <div class="p-4 text-center text-text-muted text-xs">No overdue invoices! 🎉</div>
                    @endforelse
                </div>
            </div>

            <!-- Low Stock Alerts -->
            <div class="glass-card p-0 flex flex-col h-[190px]">
                <div class="p-4 border-b border-white/5 flex items-center justify-between">
                    <h2 class="text-sm font-semibold text-text-primary">Low Stock Alerts</h2>
                    <span class="text-xs font-medium text-orange-400 bg-orange-400/10 px-2 py-0.5 rounded">{{ count($lowStock) }}</span>
                </div>
                <div class="flex-1 overflow-y-auto p-2">
                    @forelse($lowStock as $item)
                        <a href="{{ route('inventory.edit', $item->uuid) }}" class="flex items-center justify-between p-2 rounded-lg hover:bg-white/5 transition-colors group" wire:navigate>
                            <div>
                                <p class="text-xs font-medium text-text-primary truncate max-w-[120px]">{{ $item->name }}</p>
                                <p class="text-[0.65rem] text-text-muted">Reorder: {{ $item->reorder_level }}</p>
                            </div>
                            <span class="text-xs font-bold {{ $item->current_stock <= 0 ? 'text-red-400' : 'text-orange-400' }}">{{ $item->current_stock }} {{ $item->unit }}</span>
                        </a>
                    @empty
                        <div class="p-4 text-center text-text-muted text-xs">Inventory levels are looking good.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

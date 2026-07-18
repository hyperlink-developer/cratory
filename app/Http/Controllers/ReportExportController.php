<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\PurchaseInvoice;
use App\Models\GstReportPeriod;
use App\Services\GST\Gstr1ReportService;
use App\Exports\Gstr1Export;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\AccountGroup;
use Maatwebsite\Excel\Facades\Excel;

class ReportExportController extends Controller
{
    public function exportGstr1(GstReportPeriod $period)
    {
        abort_if($period->organization_id !== auth()->user()->current_organization_id, 403);
        
        $service = new Gstr1ReportService($period);
        $monthYear = \Carbon\Carbon::parse($period->period_start)->format('M-Y');
        
        return Excel::download(new Gstr1Export($service), "GSTR-1-{$monthYear}.xlsx");
    }

    public function exportSalesPdf(Request $request)
    {
        $startDate = $request->query('start', date('Y-m-01'));
        $endDate = $request->query('end', date('Y-m-t'));
        $status = $request->query('status', '');

        $query = Invoice::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->orderBy('invoice_date', 'desc');

        if ($status !== '') {
            $query->where('status', $status);
        }

        $sales = $query->get();
        $totalSales = $sales->sum('grand_total');
        $totalTax = $sales->sum('tax_total');

        $pdf = Pdf::loadView('reports.pdf.sales', [
            'sales' => $sales,
            'totalSales' => $totalSales,
            'totalTax' => $totalTax,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'org' => auth()->user()->currentOrganization
        ]);

        return $pdf->download('Sales_Report.pdf');
    }

    public function exportSalesCsv(Request $request)
    {
        $startDate = $request->query('start', date('Y-m-01'));
        $endDate = $request->query('end', date('Y-m-t'));
        $status = $request->query('status', '');

        $query = Invoice::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->whereBetween('invoice_date', [$startDate, $endDate])
            ->orderBy('invoice_date', 'desc');

        if ($status !== '') {
            $query->where('status', $status);
        }

        $sales = $query->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Sales_Report.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($sales) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Invoice #', 'Customer', 'Status', 'Subtotal', 'Tax', 'Total']);

            foreach ($sales as $sale) {
                fputcsv($file, [
                    $sale->invoice_date->format('Y-m-d'),
                    $sale->invoice_number,
                    $sale->contact?->display_name,
                    ucfirst($sale->status?->value ?? ''),
                    $sale->subtotal,
                    $sale->tax_total,
                    $sale->grand_total,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
    
    public function exportPurchasesPdf(Request $request)
    {
        $startDate = $request->query('start', date('Y-m-01'));
        $endDate = $request->query('end', date('Y-m-t'));
        $status = $request->query('status', '');

        $query = PurchaseInvoice::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->orderBy('purchase_date', 'desc');

        if ($status !== '') {
            $query->where('status', $status);
        }

        $purchases = $query->get();
        $totalPurchases = $purchases->sum('grand_total');
        $totalTax = $purchases->sum('tax_total');

        $pdf = Pdf::loadView('reports.pdf.purchases', [
            'purchases' => $purchases,
            'totalPurchases' => $totalPurchases,
            'totalTax' => $totalTax,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'org' => auth()->user()->currentOrganization
        ]);

        return $pdf->download('Purchase_Report.pdf');
    }

    public function exportPurchasesCsv(Request $request)
    {
        $startDate = $request->query('start', date('Y-m-01'));
        $endDate = $request->query('end', date('Y-m-t'));
        $status = $request->query('status', '');

        $query = PurchaseInvoice::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->whereBetween('purchase_date', [$startDate, $endDate])
            ->orderBy('purchase_date', 'desc');

        if ($status !== '') {
            $query->where('status', $status);
        }

        $purchases = $query->get();

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=Purchase_Report.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($purchases) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Date', 'Bill #', 'Vendor', 'Status', 'Subtotal', 'Tax', 'Total']);

            foreach ($purchases as $purchase) {
                fputcsv($file, [
                    $purchase->purchase_date->format('Y-m-d'),
                    $purchase->bill_number ?? $purchase->purchase_number,
                    $purchase->contact?->display_name,
                    ucfirst($purchase->status?->value ?? ''),
                    $purchase->subtotal,
                    $purchase->tax_total,
                    $purchase->grand_total,
                ]);
            }
            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }
    
    public function exportProfitLossPdf(Request $request)
    {
        $startDate = $request->query('start', date('Y-m-01'));
        $endDate = $request->query('end', date('Y-m-t'));
        $orgId = auth()->user()->current_organization_id;

        // Fetch Revenue Accounts and their balances
        $revenueGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'revenue')
            ->with(['accounts' => function ($query) use ($startDate, $endDate) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) use ($startDate, $endDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($startDate, $endDate) {
                        $q2->whereBetween('date', [$startDate, $endDate]);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) use ($startDate, $endDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($startDate, $endDate) {
                        $q2->whereBetween('date', [$startDate, $endDate]);
                    });
                }], 'debit');
            }])->get();

        // Fetch Expense Accounts and their balances
        $expenseGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'expense')
            ->with(['accounts' => function ($query) use ($startDate, $endDate) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) use ($startDate, $endDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($startDate, $endDate) {
                        $q2->whereBetween('date', [$startDate, $endDate]);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) use ($startDate, $endDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($startDate, $endDate) {
                        $q2->whereBetween('date', [$startDate, $endDate]);
                    });
                }], 'credit');
            }])->get();

        $totalRevenue = 0;
        $revenueData = [];
        foreach ($revenueGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                $balance = ($account->total_credit ?? 0) - ($account->total_debit ?? 0);
                if ($balance != 0) {
                    $accounts[] = ['name' => $account->name, 'code' => $account->code, 'balance' => $balance];
                    $groupTotal += $balance;
                }
            }
            if ($groupTotal != 0) {
                $revenueData[] = ['group' => $group->name, 'total' => $groupTotal, 'accounts' => $accounts];
                $totalRevenue += $groupTotal;
            }
        }

        $totalExpenses = 0;
        $expenseData = [];
        foreach ($expenseGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                $balance = ($account->total_debit ?? 0) - ($account->total_credit ?? 0);
                if ($balance != 0) {
                    $accounts[] = ['name' => $account->name, 'code' => $account->code, 'balance' => $balance];
                    $groupTotal += $balance;
                }
            }
            if ($groupTotal != 0) {
                $expenseData[] = ['group' => $group->name, 'total' => $groupTotal, 'accounts' => $accounts];
                $totalExpenses += $groupTotal;
            }
        }

        $netProfit = $totalRevenue - $totalExpenses;
        $reportData = [
            'revenue' => $revenueData,
            'total_revenue' => $totalRevenue,
            'expenses' => $expenseData,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];

        $layout = $request->query('layout', 'linear');

        $pdf = Pdf::loadView('reports.pdf.profit-loss', [
            'reportData' => $reportData,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'org' => auth()->user()->currentOrganization,
            'layout' => $layout
        ]);

        return $pdf->download('Profit_Loss_Report.pdf');
    }

    public function exportTrialBalancePdf(Request $request)
    {
        $asOfDate = $request->query('date', date('Y-m-t'));
        $orgId = auth()->user()->current_organization_id;

        $groups = AccountGroup::where('organization_id', $orgId)
            ->with(['accounts' => function ($query) use ($asOfDate) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'credit');
            }])
            ->orderBy('type')
            ->orderBy('code')
            ->get();

        $totalDebit = 0;
        $totalCredit = 0;
        $reportLines = [];

        foreach ($groups as $group) {
            foreach ($group->accounts as $account) {
                $sumDebit = $account->total_debit ?? 0;
                $sumCredit = $account->total_credit ?? 0;
                
                $balance = $sumDebit - $sumCredit;
                
                if ($balance == 0 && $sumDebit == 0 && $sumCredit == 0) {
                    continue;
                }

                $debit = 0;
                $credit = 0;

                if (in_array($group->type, ['asset', 'expense'])) {
                    if ($balance >= 0) {
                        $debit = $balance;
                    } else {
                        $credit = abs($balance);
                    }
                } else {
                    if ($balance <= 0) {
                        $credit = abs($balance);
                    } else {
                        $debit = $balance;
                    }
                }

                $reportLines[] = [
                    'account_name' => $account->name,
                    'account_code' => $account->code,
                    'type' => $group->type,
                    'debit' => $debit,
                    'credit' => $credit,
                ];

                $totalDebit += $debit;
                $totalCredit += $credit;
            }
        }

        $reportData = [
            'lines' => collect($reportLines)->sortBy('account_code')->values()->all(),
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'is_balanced' => round($totalDebit, 2) === round($totalCredit, 2),
        ];

        $pdf = Pdf::loadView('reports.pdf.trial-balance', [
            'reportData' => $reportData,
            'asOfDate' => $asOfDate,
            'org' => auth()->user()->currentOrganization
        ]);

        return $pdf->download('Trial_Balance.pdf');
    }

    public function exportBalanceSheetPdf(Request $request)
    {
        $asOfDate = $request->query('date', date('Y-m-t'));
        $orgId = auth()->user()->current_organization_id;

        // ASSETS
        $assetGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'asset')
            ->with(['accounts' => function ($query) use ($asOfDate) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'credit');
            }])->get();

        $totalAssets = 0;
        $assetsData = [];
        foreach ($assetGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                $balance = ($account->total_debit ?? 0) - ($account->total_credit ?? 0);
                if ($balance != 0) {
                    $accounts[] = ['name' => $account->name, 'code' => $account->code, 'balance' => $balance];
                    $groupTotal += $balance;
                }
            }
            if ($groupTotal != 0) {
                $assetsData[] = ['group' => $group->name, 'total' => $groupTotal, 'accounts' => $accounts];
                $totalAssets += $groupTotal;
            }
        }

        // LIABILITIES
        $liabilityGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'liability')
            ->with(['accounts' => function ($query) use ($asOfDate) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'debit');
            }])->get();

        $totalLiabilities = 0;
        $liabilitiesData = [];
        foreach ($liabilityGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                $balance = ($account->total_credit ?? 0) - ($account->total_debit ?? 0);
                if ($balance != 0) {
                    $accounts[] = ['name' => $account->name, 'code' => $account->code, 'balance' => $balance];
                    $groupTotal += $balance;
                }
            }
            if ($groupTotal != 0) {
                $liabilitiesData[] = ['group' => $group->name, 'total' => $groupTotal, 'accounts' => $accounts];
                $totalLiabilities += $groupTotal;
            }
        }

        // EQUITY
        $equityGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'equity')
            ->with(['accounts' => function ($query) use ($asOfDate) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'debit');
            }])->get();

        $totalEquity = 0;
        $equityData = [];
        foreach ($equityGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                $balance = ($account->total_credit ?? 0) - ($account->total_debit ?? 0);
                if ($balance != 0) {
                    $accounts[] = ['name' => $account->name, 'code' => $account->code, 'balance' => $balance];
                    $groupTotal += $balance;
                }
            }
            if ($groupTotal != 0) {
                $equityData[] = ['group' => $group->name, 'total' => $groupTotal, 'accounts' => $accounts];
                $totalEquity += $groupTotal;
            }
        }

        $revenueTotal = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'revenue')
            ->with(['accounts' => function ($query) use ($asOfDate) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'debit');
            }])->get()->sum(function ($group) {
                return $group->accounts->sum(function ($account) {
                    return ($account->total_credit ?? 0) - ($account->total_debit ?? 0);
                });
            });

        $expenseTotal = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'expense')
            ->with(['accounts' => function ($query) use ($asOfDate) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) use ($asOfDate) {
                    $q->whereHas('journalEntry', function ($q2) use ($asOfDate) {
                        $q2->where('date', '<=', $asOfDate);
                    });
                }], 'credit');
            }])->get()->sum(function ($group) {
                return $group->accounts->sum(function ($account) {
                    return ($account->total_debit ?? 0) - ($account->total_credit ?? 0);
                });
            });

        $currentYearEarnings = $revenueTotal - $expenseTotal;

        if ($currentYearEarnings != 0) {
            $equityData[] = [
                'group' => 'Retained Earnings',
                'total' => $currentYearEarnings,
                'accounts' => [
                    ['name' => 'Current Year Earnings', 'code' => '', 'balance' => $currentYearEarnings]
                ]
            ];
            $totalEquity += $currentYearEarnings;
        }

        $totalLiabilitiesAndEquity = $totalLiabilities + $totalEquity;

        $reportData = [
            'assets' => $assetsData,
            'total_assets' => $totalAssets,
            'liabilities' => $liabilitiesData,
            'total_liabilities' => $totalLiabilities,
            'equity' => $equityData,
            'total_equity' => $totalEquity,
            'total_liabilities_and_equity' => $totalLiabilitiesAndEquity,
            'is_balanced' => round($totalAssets, 2) === round($totalLiabilitiesAndEquity, 2),
        ];

        $layout = $request->query('layout', 'linear');

        $pdf = Pdf::loadView('reports.pdf.balance-sheet', [
            'reportData' => $reportData,
            'asOfDate' => $asOfDate,
            'org' => auth()->user()->currentOrganization,
            'layout' => $layout
        ]);

        return $pdf->download('Balance_Sheet.pdf');
    }
}

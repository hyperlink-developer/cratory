<?php

namespace App\Livewire\Reports;

use App\Models\AccountGroup;
use Livewire\Component;

class BalanceSheet extends Component
{
    public $asOfDate;
    public $layout = 'linear';

    public function mount()
    {
        $this->asOfDate = date('Y-m-t'); // End of current month
    }

    public function getReportDataProperty()
    {
        $orgId = auth()->user()->current_organization_id;

        // ASSETS
        $assetGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'asset')
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'credit');
            }])->get();

        $totalAssets = 0;
        $assetsData = [];
        foreach ($assetGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                // Asset normal balance is Debit
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
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'debit');
            }])->get();

        $totalLiabilities = 0;
        $liabilitiesData = [];
        foreach ($liabilityGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                // Liability normal balance is Credit
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
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'debit');
            }])->get();

        $totalEquity = 0;
        $equityData = [];
        foreach ($equityGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                // Equity normal balance is Credit
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

        // Calculate Current Year Earnings (Net Profit up to asOfDate)
        // We calculate this dynamically rather than storing it in a real account
        // Revenue
        $revenueTotal = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'revenue')
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'debit');
            }])->get()->sum(function ($group) {
                return $group->accounts->sum(function ($account) {
                    return ($account->total_credit ?? 0) - ($account->total_debit ?? 0);
                });
            });

        // Expenses
        $expenseTotal = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'expense')
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->where('date', '<=', $this->asOfDate);
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

        return [
            'assets' => $assetsData,
            'total_assets' => $totalAssets,
            'liabilities' => $liabilitiesData,
            'total_liabilities' => $totalLiabilities,
            'equity' => $equityData,
            'total_equity' => $totalEquity,
            'total_liabilities_and_equity' => $totalLiabilitiesAndEquity,
            'is_balanced' => round($totalAssets, 2) === round($totalLiabilitiesAndEquity, 2),
        ];
    }

    public function render()
    {
        return view('livewire.reports.balance-sheet', [
            'reportData' => $this->reportData,
        ])->layout('components.layouts.app', ['title' => 'Balance Sheet']);
    }
}

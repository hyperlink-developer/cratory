<?php

namespace App\Livewire\Reports;

use App\Models\AccountGroup;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ProfitLossReport extends Component
{
    public $startDate;
    public $endDate;
    public $layout = 'linear';

    public function mount()
    {
        $this->startDate = date('Y-m-01'); // Start of current month
        $this->endDate = date('Y-m-t'); // End of current month
    }

    public function getReportDataProperty()
    {
        $orgId = auth()->user()->current_organization_id;

        // Fetch Revenue Accounts and their balances
        $revenueGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'revenue')
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->whereBetween('date', [$this->startDate, $this->endDate]);
                    });
                }], 'credit')
                ->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->whereBetween('date', [$this->startDate, $this->endDate]);
                    });
                }], 'debit');
            }])->get();

        // Fetch Expense Accounts and their balances
        $expenseGroups = AccountGroup::where('organization_id', $orgId)
            ->where('type', 'expense')
            ->with(['accounts' => function ($query) {
                $query->withSum(['journalEntryLines as total_debit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->whereBetween('date', [$this->startDate, $this->endDate]);
                    });
                }], 'debit')
                ->withSum(['journalEntryLines as total_credit' => function ($q) {
                    $q->whereHas('journalEntry', function ($q2) {
                        $q2->whereBetween('date', [$this->startDate, $this->endDate]);
                    });
                }], 'credit');
            }])->get();

        $totalRevenue = 0;
        $revenueData = [];
        foreach ($revenueGroups as $group) {
            $groupTotal = 0;
            $accounts = [];
            foreach ($group->accounts as $account) {
                // For revenue, normal balance is Credit. (Credit - Debit)
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
                // For expenses, normal balance is Debit. (Debit - Credit)
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

        return [
            'revenue' => $revenueData,
            'total_revenue' => $totalRevenue,
            'expenses' => $expenseData,
            'total_expenses' => $totalExpenses,
            'net_profit' => $netProfit,
        ];
    }

    public function render()
    {
        return view('livewire.reports.profit-loss-report', [
            'reportData' => $this->reportData,
        ])->layout('components.layouts.app', ['title' => 'Profit and Loss Statement']);
    }
}

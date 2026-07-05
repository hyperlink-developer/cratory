<?php

namespace App\Livewire\Reports;

use App\Models\AccountGroup;
use Livewire\Component;

class TrialBalance extends Component
{
    public $asOfDate;

    public function mount()
    {
        $this->asOfDate = date('Y-m-t'); // End of current month
    }

    public function getReportDataProperty()
    {
        $orgId = auth()->user()->current_organization_id;

        // Fetch all Account Groups and their Accounts
        $groups = AccountGroup::where('organization_id', $orgId)
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
            }])
            ->orderBy('type') // asset, expense, equity, liability, revenue
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
                    continue; // Skip accounts with no activity
                }

                $debit = 0;
                $credit = 0;

                // Determine normal balance based on account type
                if (in_array($group->type, ['asset', 'expense'])) {
                    // Normal balance is debit
                    if ($balance >= 0) {
                        $debit = $balance;
                    } else {
                        $credit = abs($balance);
                    }
                } else {
                    // Normal balance is credit
                    // balance = debit - credit, so if negative, it has a credit balance
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

        return [
            'lines' => collect($reportLines)->sortBy('account_code')->values()->all(),
            'total_debit' => $totalDebit,
            'total_credit' => $totalCredit,
            'is_balanced' => round($totalDebit, 2) === round($totalCredit, 2),
        ];
    }

    public function render()
    {
        return view('livewire.reports.trial-balance', [
            'reportData' => $this->reportData,
        ])->layout('components.layouts.app', ['title' => 'Trial Balance']);
    }
}

<?php

namespace App\Livewire\Accounting;

use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ManualJournal extends Component
{
    public $date;
    public $referenceNumber = '';
    public $description = '';

    public $lines = [];
    public $accounts = [];

    public function mount()
    {
        $this->date = date('Y-m-d');
        $this->accounts = Account::where('organization_id', auth()->user()->current_organization_id)
            ->where('is_active', true)
            ->orderBy('code')
            ->orderBy('name')
            ->get();

        $this->addLine();
        $this->addLine();
    }

    public function addLine()
    {
        $this->lines[] = [
            'account_id' => '',
            'debit' => 0,
            'credit' => 0,
            'description' => '',
        ];
    }

    public function removeLine($index)
    {
        if (count($this->lines) > 2) {
            unset($this->lines[$index]);
            $this->lines = array_values($this->lines);
        }
    }

    public function getTotalDebitProperty()
    {
        return collect($this->lines)->sum(function ($line) {
            return floatval($line['debit'] ?? 0);
        });
    }

    public function getTotalCreditProperty()
    {
        return collect($this->lines)->sum(function ($line) {
            return floatval($line['credit'] ?? 0);
        });
    }

    public function save()
    {
        $this->validate([
            'date' => 'required|date',
            'referenceNumber' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'numeric|min:0',
            'lines.*.credit' => 'numeric|min:0',
        ]);

        $totalDebit = round($this->getTotalDebitProperty(), 2);
        $totalCredit = round($this->getTotalCreditProperty(), 2);

        if ($totalDebit !== $totalCredit) {
            $this->addError('totals', 'Total Debits must equal Total Credits to post a journal entry.');
            return;
        }

        if ($totalDebit == 0) {
            $this->addError('totals', 'Journal entry must have a non-zero amount.');
            return;
        }

        DB::transaction(function () {
            $entry = JournalEntry::create([
                'organization_id' => auth()->user()->current_organization_id,
                'date' => $this->date,
                'reference_number' => $this->referenceNumber,
                'description' => $this->description,
            ]);

            foreach ($this->lines as $line) {
                if (floatval($line['debit']) > 0 || floatval($line['credit']) > 0) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_id' => $line['account_id'],
                        'debit' => floatval($line['debit'] ?? 0),
                        'credit' => floatval($line['credit'] ?? 0),
                        'description' => $line['description'] ?? null,
                    ]);
                }
            }
        });

        // Reset form
        $this->date = date('Y-m-d');
        $this->referenceNumber = '';
        $this->description = '';
        $this->lines = [];
        $this->addLine();
        $this->addLine();

        $this->dispatch('notify', message: 'Manual journal entry posted successfully.');
    }

    public function render()
    {
        return view('livewire.accounting.manual-journal')
            ->layout('layouts.app');
    }
}

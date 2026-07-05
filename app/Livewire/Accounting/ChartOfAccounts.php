<?php

namespace App\Livewire\Accounting;

use App\Models\Account;
use App\Models\AccountGroup;
use Livewire\Component;

class ChartOfAccounts extends Component
{
    public $groups;

    public $showAccountModal = false;
    public $showGroupModal = false;

    // Account Form
    public $accountId = null;
    public $accountGroupId = '';
    public $accountName = '';
    public $accountCode = '';
    public $accountDescription = '';

    // Group Form
    public $groupId = null;
    public $groupName = '';
    public $groupCode = '';
    public $groupType = 'asset';

    protected $rules = [
        'accountGroupId' => 'required|exists:account_groups,id',
        'accountName' => 'required|string|max:255',
        'accountCode' => 'nullable|string|max:255',
        'accountDescription' => 'nullable|string',

        'groupName' => 'required|string|max:255',
        'groupCode' => 'nullable|string|max:255',
        'groupType' => 'required|in:asset,liability,equity,revenue,expense',
    ];

    public function mount()
    {
        $this->loadData();
    }

    public function loadData()
    {
        $this->groups = AccountGroup::where('organization_id', auth()->user()->current_organization_id)
            ->with(['accounts' => function ($query) {
                $query->orderBy('code')->orderBy('name');
            }])
            ->orderBy('type')
            ->orderBy('name')
            ->get();
    }

    public function openAccountModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $account = Account::where('organization_id', auth()->user()->current_organization_id)->findOrFail($id);
            $this->accountId = $account->id;
            $this->accountGroupId = $account->account_group_id;
            $this->accountName = $account->name;
            $this->accountCode = $account->code;
            $this->accountDescription = $account->description;
        } else {
            $this->accountId = null;
            $this->accountGroupId = $this->groups->first()->id ?? '';
            $this->accountName = '';
            $this->accountCode = '';
            $this->accountDescription = '';
        }
        $this->showAccountModal = true;
    }

    public function saveAccount()
    {
        $this->validate([
            'accountGroupId' => 'required|exists:account_groups,id',
            'accountName' => 'required|string|max:255',
            'accountCode' => 'nullable|string|max:255',
            'accountDescription' => 'nullable|string',
        ]);

        $organizationId = auth()->user()->current_organization_id;

        // Ensure unique code per organization
        if ($this->accountCode) {
            $exists = Account::where('organization_id', $organizationId)
                ->where('code', $this->accountCode)
                ->where('id', '!=', $this->accountId)
                ->exists();

            if ($exists) {
                $this->addError('accountCode', 'This account code is already in use.');
                return;
            }
        }

        if ($this->accountId) {
            $account = Account::where('organization_id', $organizationId)->findOrFail($this->accountId);
            $account->update([
                'account_group_id' => $this->accountGroupId,
                'name' => $this->accountName,
                'code' => $this->accountCode,
                'description' => $this->accountDescription,
            ]);
        } else {
            Account::create([
                'organization_id' => $organizationId,
                'account_group_id' => $this->accountGroupId,
                'name' => $this->accountName,
                'code' => $this->accountCode,
                'description' => $this->accountDescription,
                'is_system' => false,
                'is_active' => true,
            ]);
        }

        $this->showAccountModal = false;
        $this->loadData();
    }

    public function openGroupModal($id = null)
    {
        $this->resetValidation();
        if ($id) {
            $group = AccountGroup::where('organization_id', auth()->user()->current_organization_id)->findOrFail($id);
            $this->groupId = $group->id;
            $this->groupName = $group->name;
            $this->groupCode = $group->code;
            $this->groupType = $group->type;
        } else {
            $this->groupId = null;
            $this->groupName = '';
            $this->groupCode = '';
            $this->groupType = 'asset';
        }
        $this->showGroupModal = true;
    }

    public function saveGroup()
    {
        $this->validate([
            'groupName' => 'required|string|max:255',
            'groupCode' => 'nullable|string|max:255',
            'groupType' => 'required|in:asset,liability,equity,revenue,expense',
        ]);

        $organizationId = auth()->user()->current_organization_id;

        if ($this->groupId) {
            $group = AccountGroup::where('organization_id', $organizationId)->findOrFail($this->groupId);
            $group->update([
                'name' => $this->groupName,
                'code' => $this->groupCode,
                'type' => $this->groupType,
            ]);
        } else {
            AccountGroup::create([
                'organization_id' => $organizationId,
                'name' => $this->groupName,
                'code' => $this->groupCode,
                'type' => $this->groupType,
                'is_system' => false,
            ]);
        }

        $this->showGroupModal = false;
        $this->loadData();
    }

    public function render()
    {
        return view('livewire.accounting.chart-of-accounts')
            ->layout('layouts.app');
    }
}

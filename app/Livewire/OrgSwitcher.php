<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Livewire\Component;

class OrgSwitcher extends Component
{
    public bool $open = false;
    public ?int $currentOrgId = null;

    public function mount(): void
    {
        $this->currentOrgId = auth()->user()->current_organization_id;
    }

    public function switchOrg(int $orgId): void
    {
        auth()->user()->switchOrganization($orgId);
        $this->currentOrgId = $orgId;
        $this->open = false;

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function getOrganizationsProperty(): Collection
    {
        return auth()->user()->activeOrganizations()->get();
    }

    public function render()
    {
        return view('livewire.org-switcher');
    }
}

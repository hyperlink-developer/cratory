<?php

namespace App\Livewire\Onboarding;

use App\Enums\BusinessCategory;
use App\Enums\OrgUserRole;
use App\Enums\OrgUserStatus;
use App\Enums\OrganizationType;
use App\Models\Organization;
use Database\Seeders\OrganizationDefaultsSeeder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class OrganizationWizard extends Component
{
    use WithFileUploads;

    public int $step = 1;
    public int $totalSteps = 5;

    // Step 1: Business basics
    public string $orgName = '';
    public string $orgType = '';
    public string $businessCategory = '';

    public string $panNumber = '';
    public string $gstNumber = '';
    public bool $isComposition = false;

    // Step 3: Address
    public string $addressLine1 = '';
    public string $addressLine2 = '';
    public string $city = '';
    public string $state = '';
    public string $pincode = '';
    public string $country = 'India';

    // Step 4: Commander details
    public string $commanderName = '';
    public string $commanderPhone = '';
    public string $commanderEmail = '';

    // Step 5 uses summary of all above

    // Prefix
    public string $invoicePrefix = '';

    protected function rules(): array
    {
        return match ($this->step) {
            1 => [
                'orgName' => 'required|string|max:255',
                'orgType' => 'required|string',
                'businessCategory' => 'required|string',
            ],
            2 => [
                'panNumber' => 'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/',
                'gstNumber' => 'nullable|string|size:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/',
                'isComposition' => 'boolean',
            ],
            3 => [
                'addressLine1' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'pincode' => 'required|string|max:10',
            ],
            4 => [
                'commanderName' => 'required|string|max:255',
                'commanderPhone' => 'nullable|string|max:15',
                'commanderEmail' => 'required|email',
            ],
            default => [],
        };
    }

    protected $messages = [
        'panNumber.regex' => 'PAN should be in format: ABCDE1234F',
        'gstNumber.regex' => 'GSTIN should be a valid 15-character format',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        $this->commanderName = $user->name;
        $this->commanderEmail = $user->email;
        $this->commanderPhone = $user->phone ?? '';
    }

    public function nextStep(): void
    {
        $this->validate();
        $this->step = min($this->step + 1, $this->totalSteps);
    }

    public function previousStep(): void
    {
        $this->step = max($this->step - 1, 1);
    }

    public function goToStep(int $step): void
    {
        if ($step < $this->step) {
            $this->step = $step;
        }
    }

    public function updatedOrgName(): void
    {
        if (empty($this->invoicePrefix) && !empty($this->orgName)) {
            $words = explode(' ', trim($this->orgName));
            $this->invoicePrefix = strtoupper(
                count($words) >= 2
                    ? substr($words[0], 0, 1) . substr($words[1], 0, 1) . substr(end($words), 0, 1)
                    : substr($this->orgName, 0, 3)
            );
        }
    }

    public function createOrganization(): void
    {
        DB::transaction(function () {
            $user = auth()->user();

            // Update commander details
            $user->update([
                'name' => $this->commanderName,
                'phone' => $this->commanderPhone ?: null,
                'email' => $this->commanderEmail,
            ]);

            // Create organization
            $org = Organization::create([
                'name' => $this->orgName,
                'type' => $this->orgType,
                'business_category' => $this->businessCategory,
                'pan_number' => strtoupper($this->panNumber),
                'gst_number' => $this->gstNumber ? strtoupper($this->gstNumber) : null,
                'is_composition_tax_payer' => $this->isComposition,
                'address_line_1' => $this->addressLine1,
                'address_line_2' => $this->addressLine2 ?: null,
                'city' => $this->city,
                'state' => $this->state,
                'pincode' => $this->pincode,
                'country' => $this->country,
                'invoice_prefix' => $this->invoicePrefix ?: 'CRT',
                'created_by' => $user->id,
            ]);

            // Attach user as commander
            $org->users()->attach($user->id, [
                'role' => OrgUserRole::Commander->value,
                'is_default_org' => !$user->hasOrganizations(),
                'status' => OrgUserStatus::Active->value,
            ]);

            // Seed defaults (tax rates + invoice templates)
            OrganizationDefaultsSeeder::seedForOrganization($org);

            // Switch to new org
            $user->switchOrganization($org->id);
        });

        $this->redirect(route('dashboard'), navigate: true);
    }

    public function getOrganizationTypesProperty(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], OrganizationType::cases());
    }

    public function getBusinessCategoriesProperty(): array
    {
        return array_map(fn ($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], BusinessCategory::cases());
    }

    public function render()
    {
        return view('livewire.onboarding.organization-wizard')
            ->layout('components.layouts.auth', ['title' => 'Setup Organization']);
    }
}

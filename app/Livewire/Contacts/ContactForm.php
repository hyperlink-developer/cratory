<?php

namespace App\Livewire\Contacts;

use App\Enums\ContactType;
use App\Models\Contact;
use Livewire\Component;

class ContactForm extends Component
{
    public ?Contact $contact = null;

    public string $type = 'customer';
    public string $name = '';
    public string $displayName = '';
    public string $email = '';
    public string $phone = '';
    public string $gstNumber = '';
    public string $panNumber = '';
    public string $openingBalance = '0.00';

    // Billing Address
    public string $billingAddressLine1 = '';
    public string $billingAddressLine2 = '';
    public string $billingCity = '';
    public string $billingState = '';
    public string $billingPincode = '';
    public string $billingCountry = 'India';

    // Shipping Address
    public bool $sameAsBilling = true;
    public string $shippingAddressLine1 = '';
    public string $shippingAddressLine2 = '';
    public string $shippingCity = '';
    public string $shippingState = '';
    public string $shippingPincode = '';
    public string $shippingCountry = 'India';

    protected function rules()
    {
        return [
            'type' => 'required|string',
            'name' => 'required|string|max:255',
            'displayName' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'gstNumber' => 'nullable|string|size:15|regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z][1-9A-Z]Z[0-9A-Z]$/i',
            'panNumber' => 'nullable|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]$/i',
            'openingBalance' => 'required|numeric|min:0',

            'billingAddressLine1' => 'nullable|string|max:255',
            'billingAddressLine2' => 'nullable|string|max:255',
            'billingCity' => 'nullable|string|max:100',
            'billingState' => 'nullable|string|max:100',
            'billingPincode' => 'nullable|string|max:10',
            'billingCountry' => 'nullable|string|max:100',

            'shippingAddressLine1' => 'nullable|string|max:255',
            'shippingAddressLine2' => 'nullable|string|max:255',
            'shippingCity' => 'nullable|string|max:100',
            'shippingState' => 'nullable|string|max:100',
            'shippingPincode' => 'nullable|string|max:10',
            'shippingCountry' => 'nullable|string|max:100',
        ];
    }

    public function mount(Contact $contact = null)
    {
        if ($contact && $contact->exists) {
            $this->contact = $contact;
            $this->type = $contact->type->value;
            $this->name = $contact->name;
            $this->displayName = $contact->display_name;
            $this->email = $contact->email ?? '';
            $this->phone = $contact->phone ?? '';
            $this->gstNumber = $contact->gst_number ?? '';
            $this->panNumber = $contact->pan_number ?? '';
            $this->openingBalance = $contact->opening_balance ?? '0.00';

            $this->billingAddressLine1 = $contact->billing_address_line_1 ?? '';
            $this->billingAddressLine2 = $contact->billing_address_line_2 ?? '';
            $this->billingCity = $contact->billing_city ?? '';
            $this->billingState = $contact->billing_state ?? '';
            $this->billingPincode = $contact->billing_pincode ?? '';
            $this->billingCountry = $contact->billing_country ?? 'India';

            $this->shippingAddressLine1 = $contact->shipping_address_line_1 ?? '';
            $this->shippingAddressLine2 = $contact->shipping_address_line_2 ?? '';
            $this->shippingCity = $contact->shipping_city ?? '';
            $this->shippingState = $contact->shipping_state ?? '';
            $this->shippingPincode = $contact->shipping_pincode ?? '';
            $this->shippingCountry = $contact->shipping_country ?? 'India';

            $this->sameAsBilling = (
                $this->billingAddressLine1 === $this->shippingAddressLine1 &&
                $this->billingCity === $this->shippingCity &&
                $this->billingState === $this->shippingState &&
                $this->billingPincode === $this->shippingPincode
            );
        }
    }

    public function updatedSameAsBilling($value)
    {
        if ($value) {
            $this->syncShippingWithBilling();
        }
    }

    public function updated($propertyName)
    {
        if ($this->sameAsBilling && str_starts_with($propertyName, 'billing')) {
            $this->syncShippingWithBilling();
        }
    }

    private function syncShippingWithBilling()
    {
        $this->shippingAddressLine1 = $this->billingAddressLine1;
        $this->shippingAddressLine2 = $this->billingAddressLine2;
        $this->shippingCity = $this->billingCity;
        $this->shippingState = $this->billingState;
        $this->shippingPincode = $this->billingPincode;
        $this->shippingCountry = $this->billingCountry;
    }

    public function save()
    {
        $this->validate();

        if ($this->sameAsBilling) {
            $this->syncShippingWithBilling();
        }

        $data = [
            'type' => $this->type,
            'name' => $this->name,
            'display_name' => $this->displayName ?: $this->name,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'gst_number' => $this->gstNumber ? strtoupper($this->gstNumber) : null,
            'pan_number' => $this->panNumber ? strtoupper($this->panNumber) : null,
            'opening_balance' => $this->openingBalance,

            'billing_address_line_1' => $this->billingAddressLine1,
            'billing_address_line_2' => $this->billingAddressLine2,
            'billing_city' => $this->billingCity,
            'billing_state' => $this->billingState,
            'billing_pincode' => $this->billingPincode,
            'billing_country' => $this->billingCountry,

            'shipping_address_line_1' => $this->shippingAddressLine1,
            'shipping_address_line_2' => $this->shippingAddressLine2,
            'shipping_city' => $this->shippingCity,
            'shipping_state' => $this->shippingState,
            'shipping_pincode' => $this->shippingPincode,
            'shipping_country' => $this->shippingCountry,
        ];

        if ($this->contact && $this->contact->exists) {
            $this->contact->update($data);
        } else {
            $data['organization_id'] = auth()->user()->current_organization_id;
            Contact::create($data);
        }

        $this->redirect(route('contacts.index'), navigate: true);
    }

    public function getContactTypesProperty()
    {
        return array_map(fn($t) => ['value' => $t->value, 'label' => $t->label()], ContactType::cases());
    }

    public function render()
    {
        return view('livewire.contacts.contact-form')->layout('components.layouts.app', [
            'title' => $this->contact && $this->contact->exists ? 'Edit Contact' : 'Create Contact'
        ]);
    }
}

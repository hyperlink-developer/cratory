<?php

namespace App\Livewire\Settings;

use App\Models\Warehouse;
use Livewire\Component;

class Warehouses extends Component
{
    public $warehouses;
    public $isModalOpen = false;
    public $editId = null;

    public $name = '';
    public $code = '';
    public $address_line_1 = '';
    public $address_line_2 = '';
    public $city = '';
    public $state = '';
    public $pincode = '';
    public $is_primary = false;

    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'nullable|string|max:50',
        'address_line_1' => 'nullable|string|max:255',
        'address_line_2' => 'nullable|string|max:255',
        'city' => 'nullable|string|max:100',
        'state' => 'nullable|string|max:100',
        'pincode' => 'nullable|string|max:20',
        'is_primary' => 'boolean',
    ];

    public function mount()
    {
        $this->loadWarehouses();
    }

    public function loadWarehouses()
    {
        $this->warehouses = Warehouse::where('organization_id', auth()->user()->current_organization_id)->get();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->isModalOpen = true;
    }

    public function edit($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $this->editId = $id;
        $this->name = $warehouse->name;
        $this->code = $warehouse->code;
        $this->address_line_1 = $warehouse->address_line_1;
        $this->address_line_2 = $warehouse->address_line_2;
        $this->city = $warehouse->city;
        $this->state = $warehouse->state;
        $this->pincode = $warehouse->pincode;
        $this->is_primary = $warehouse->is_primary;

        $this->isModalOpen = true;
    }

    public function save()
    {
        $this->validate();

        if ($this->is_primary) {
            // Unset other primary warehouses
            Warehouse::where('organization_id', auth()->user()->current_organization_id)
                ->update(['is_primary' => false]);
        }

        Warehouse::updateOrCreate(
            ['id' => $this->editId],
            [
                'organization_id' => auth()->user()->current_organization_id,
                'name' => $this->name,
                'code' => $this->code,
                'address_line_1' => $this->address_line_1,
                'address_line_2' => $this->address_line_2,
                'city' => $this->city,
                'state' => $this->state,
                'pincode' => $this->pincode,
                'is_primary' => $this->is_primary,
            ]
        );

        $this->dispatch('notify', ['message' => $this->editId ? 'Warehouse updated successfully.' : 'Warehouse created successfully.']);
        $this->closeModal();
        $this->loadWarehouses();
    }

    public function delete($id)
    {
        Warehouse::find($id)->delete();
        $this->dispatch('notify', ['message' => 'Warehouse deleted successfully.', 'type' => 'success']);
        $this->loadWarehouses();
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->editId = null;
        $this->name = '';
        $this->code = '';
        $this->address_line_1 = '';
        $this->address_line_2 = '';
        $this->city = '';
        $this->state = '';
        $this->pincode = '';
        $this->is_primary = false;
    }

    public function render()
    {
        return view('livewire.settings.warehouses')->layout('components.layouts.app', ['title' => 'Warehouses']);
    }
}

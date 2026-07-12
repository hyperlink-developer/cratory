<?php

namespace App\Livewire\Settings;

use App\Models\UnitOfMeasure;
use Livewire\Component;

class UnitOfMeasures extends Component
{
    public $uoms;
    
    public ?int $editingId = null;
    public string $name = '';
    public string $abbreviation = '';

    public bool $showModal = false;

    public function mount()
    {
        $this->loadUoms();
    }

    public function loadUoms()
    {
        $this->uoms = UnitOfMeasure::orderBy('name')->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'abbreviation' => 'required|string|max:50',
        ];
    }

    public function create()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(string $uuid)
    {
        $this->resetValidation();
        $uom = UnitOfMeasure::where('uuid', $uuid)->firstOrFail();
        
        $this->editingId = $uom->id;
        $this->name = $uom->name;
        $this->abbreviation = $uom->abbreviation;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'abbreviation' => $this->abbreviation,
        ];

        if ($this->editingId) {
            $uom = UnitOfMeasure::findOrFail($this->editingId);
            $uom->update($data);
            $message = 'Unit of Measure updated successfully.';
        } else {
            $data['organization_id'] = auth()->user()->current_organization_id;
            UnitOfMeasure::create($data);
            $message = 'Unit of Measure added successfully.';
        }

        $this->loadUoms();
        $this->showModal = false;
        $this->dispatch('notify', ['message' => $message]);
    }

    public function toggleActive(string $uuid)
    {
        $uom = UnitOfMeasure::where('uuid', $uuid)->firstOrFail();
        $uom->update(['is_active' => !$uom->is_active]);
        $this->loadUoms();
    }

    public function delete(string $uuid)
    {
        $uom = UnitOfMeasure::where('uuid', $uuid)->firstOrFail();
        $uom->delete();
        $this->loadUoms();
        $this->dispatch('notify', ['message' => 'Unit of Measure deleted.']);
    }

    private function resetForm()
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->abbreviation = '';
    }

    public function render()
    {
        return view('livewire.settings.unit-of-measures')->layout('components.layouts.app', ['title' => 'Units of Measure Settings']);
    }
}

<?php

namespace App\Livewire\Settings;

use App\Models\TaxRate;
use Livewire\Component;

class TaxRates extends Component
{
    public $taxRates;
    
    public ?int $editingId = null;
    public string $name = '';
    public string $percentage = '';
    public bool $isGst = false;

    public bool $showModal = false;

    public function mount()
    {
        $this->loadTaxRates();
    }

    public function loadTaxRates()
    {
        $this->taxRates = TaxRate::orderBy('percentage')->get();
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'percentage' => 'required|numeric|min:0|max:100',
            'isGst' => 'boolean',
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
        $tax = TaxRate::where('uuid', $uuid)->firstOrFail();
        
        $this->editingId = $tax->id;
        $this->name = $tax->name;
        $this->percentage = rtrim(rtrim($tax->percentage, '0'), '.');
        $this->isGst = $tax->is_gst;
        
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'percentage' => $this->percentage,
            'is_gst' => $this->isGst,
        ];

        if ($this->editingId) {
            $tax = TaxRate::findOrFail($this->editingId);
            $tax->update($data);
            $message = 'Tax rate updated successfully.';
        } else {
            $data['organization_id'] = auth()->user()->current_organization_id;
            TaxRate::create($data);
            $message = 'Tax rate added successfully.';
        }

        $this->loadTaxRates();
        $this->showModal = false;
        $this->dispatch('notify', ['message' => $message]);
    }

    public function toggleActive(string $uuid)
    {
        $tax = TaxRate::where('uuid', $uuid)->firstOrFail();
        $tax->update(['is_active' => !$tax->is_active]);
        $this->loadTaxRates();
    }

    public function delete(string $uuid)
    {
        $tax = TaxRate::where('uuid', $uuid)->firstOrFail();
        // Note: In a real app we might prevent deletion if used in invoices, but soft deletes handles safety, or we just deactivate.
        // TaxRate doesn't have SoftDeletes currently, so let's just delete or deactivate.
        $tax->delete();
        $this->loadTaxRates();
        $this->dispatch('notify', ['message' => 'Tax rate deleted.']);
    }

    private function resetForm()
    {
        $this->resetValidation();
        $this->editingId = null;
        $this->name = '';
        $this->percentage = '';
        $this->isGst = false;
    }

    public function render()
    {
        return view('livewire.settings.tax-rates')->layout('components.layouts.app', ['title' => 'Tax Rates Settings']);
    }
}

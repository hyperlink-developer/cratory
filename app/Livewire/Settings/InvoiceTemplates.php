<?php

namespace App\Livewire\Settings;

use App\Models\InvoiceTemplate;
use Livewire\Component;

class InvoiceTemplates extends Component
{
    public $templates = [];
    public ?InvoiceTemplate $activeTemplate = null;

    // Form fields
    public string $name = 'Standard';
    public string $slug = 'standard';
    public string $colorPrimary = '#4F46E5';
    public string $colorSecondary = '#F59E0B';
    public string $fontChoice = 'Helvetica';
    public array $showFields = [
        'shipping_address' => true,
        'hsn' => true,
        'tax_details' => true,
    ];

    public function mount()
    {
        $this->loadTemplates();
    }

    public function loadTemplates()
    {
        $organization = auth()->user()->currentOrganization;
        $this->templates = InvoiceTemplate::where('organization_id', $organization->id)->get();
        
        if ($this->templates->isEmpty()) {
            // Create default template if none exists
            $this->activeTemplate = InvoiceTemplate::create([
                'organization_id' => $organization->id,
                'name' => 'Standard',
                'slug' => 'standard',
                'is_default' => true,
                'color_primary' => '#4F46E5',
                'color_secondary' => '#F59E0B',
                'show_fields' => InvoiceTemplate::defaultShowFields(),
                'font_choice' => 'Helvetica',
            ]);
            $this->templates = collect([$this->activeTemplate]);
        } else {
            $this->activeTemplate = $this->templates->where('is_default', true)->first() ?? $this->templates->first();
        }

        $this->fillForm();
    }

    public function fillForm()
    {
        if ($this->activeTemplate) {
            $this->name = $this->activeTemplate->name;
            $this->slug = $this->activeTemplate->slug ?? 'standard';
            $this->colorPrimary = $this->activeTemplate->color_primary;
            $this->colorSecondary = $this->activeTemplate->color_secondary;
            $this->fontChoice = $this->activeTemplate->font_choice ?? 'Helvetica';
            $this->showFields = array_merge($this->showFields, $this->activeTemplate->show_fields ?? []);
        }
    }

    public function selectTemplate(string $slug)
    {
        $this->slug = $slug;
        $this->name = ucfirst($slug);
    }

    public function save()
    {
        $this->validate([
            'slug' => 'required|in:standard,modern,minimal,elegant',
            'colorPrimary' => 'required|string',
            'colorSecondary' => 'required|string',
        ]);

        if ($this->activeTemplate) {
            $this->activeTemplate->update([
                'name' => ucfirst($this->slug),
                'slug' => $this->slug,
                'color_primary' => $this->colorPrimary,
                'color_secondary' => $this->colorSecondary,
                'font_choice' => $this->fontChoice,
                'show_fields' => $this->showFields,
            ]);
        }

        $this->dispatch('notify', ['message' => 'Template settings saved successfully!']);
    }

    public function render()
    {
        return view('livewire.settings.invoice-templates')->layout('components.layouts.app', [
            'title' => 'Invoice Templates'
        ]);
    }
}

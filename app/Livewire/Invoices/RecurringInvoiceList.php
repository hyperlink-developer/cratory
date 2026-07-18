<?php

namespace App\Livewire\Invoices;

use App\Models\RecurringInvoiceTemplate;
use Livewire\Component;

class RecurringInvoiceList extends Component
{
    public function toggleActive($id)
    {
        $template = RecurringInvoiceTemplate::where('organization_id', auth()->user()->current_organization_id)
            ->findOrFail($id);
            
        $template->update([
            'is_active' => !$template->is_active
        ]);
        
        $this->dispatch('notify', [
            'message' => 'Template ' . ($template->is_active ? 'activated' : 'paused') . ' successfully.',
            'type' => 'success'
        ]);
    }

    public function render()
    {
        $templates = RecurringInvoiceTemplate::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('livewire.invoices.recurring-invoice-list', [
            'templates' => $templates,
        ])->layout('components.layouts.app', ['title' => 'Recurring Invoices']);
    }
}

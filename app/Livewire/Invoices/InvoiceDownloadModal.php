<?php

namespace App\Livewire\Invoices;

use App\Models\Invoice;
use App\Models\InvoiceTemplate;
use Livewire\Attributes\On;
use Livewire\Component;

class InvoiceDownloadModal extends Component
{
    public bool $isOpen = false;
    public ?Invoice $invoice = null;

    public $templateId;
    public $showFields = [];

    // All available templates
    public $templates = [];

    #[On('open-download-modal')]
    public function openModal($invoiceUuid)
    {
        $this->invoice = Invoice::where('uuid', $invoiceUuid)
            ->where('organization_id', auth()->user()->current_organization_id)
            ->firstOrFail();

        $this->templates = auth()->user()->currentOrganization->invoiceTemplates;
        
        $defaultTemplate = $this->templates->firstWhere('is_default', true) ?? $this->templates->first();
        $this->templateId = $defaultTemplate->id ?? null;
        
        $this->showFields = $defaultTemplate->show_fields ?? InvoiceTemplate::defaultShowFields();
        
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->invoice = null;
    }

    public function download()
    {
        if (!$this->invoice) return;

        $queryParams = http_build_query([
            'template_id' => $this->templateId,
            'show_fields' => $this->showFields,
        ]);

        $url = route('invoices.pdf', $this->invoice->uuid) . '?' . $queryParams;
        
        $this->closeModal();
        
        $this->redirect($url);
    }

    public function updatedTemplateId($value)
    {
        if ($value) {
            $template = $this->templates->firstWhere('id', $value);
            if ($template) {
                $this->showFields = $template->show_fields ?? InvoiceTemplate::defaultShowFields();
            }
        }
    }

    public function render()
    {
        return view('livewire.invoices.invoice-download-modal');
    }
}

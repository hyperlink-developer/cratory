<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class DocumentNumbering extends Component
{
    public $invoicePrefix = 'INV';
    public $invoiceFormat = '{PREFIX}-{FY}-{SEQ}';

    public $receiptPrefix = 'REC';
    public $receiptFormat = '{PREFIX}-{FY}-{SEQ}';

    public $voucherPrefix = 'PAY';
    public $voucherFormat = '{PREFIX}-{FY}-{SEQ}';

    public function mount()
    {
        $org = auth()->user()->currentOrganization;
        $settings = $org->document_settings ?? [];

        $this->invoicePrefix = $settings['invoice']['prefix'] ?? $org->invoice_prefix ?? 'INV';
        $this->invoiceFormat = $settings['invoice']['format'] ?? '{PREFIX}-{DOC_TYPE}-{FY}-{SEQ}';

        $this->receiptPrefix = $settings['receipt']['prefix'] ?? 'REC';
        $this->receiptFormat = $settings['receipt']['format'] ?? '{PREFIX}-{FY}-{SEQ}';

        $this->voucherPrefix = $settings['voucher']['prefix'] ?? 'PAY';
        $this->voucherFormat = $settings['voucher']['format'] ?? '{PREFIX}-{FY}-{SEQ}';
    }

    public function save()
    {
        $this->validate([
            'invoicePrefix' => 'required|string|max:10',
            'invoiceFormat' => 'required|string|max:50',
            'receiptPrefix' => 'required|string|max:10',
            'receiptFormat' => 'required|string|max:50',
            'voucherPrefix' => 'required|string|max:10',
            'voucherFormat' => 'required|string|max:50',
        ]);

        $org = auth()->user()->currentOrganization;
        
        $settings = $org->document_settings ?? [];
        
        $settings['invoice'] = ['prefix' => $this->invoicePrefix, 'format' => $this->invoiceFormat];
        $settings['receipt'] = ['prefix' => $this->receiptPrefix, 'format' => $this->receiptFormat];
        $settings['voucher'] = ['prefix' => $this->voucherPrefix, 'format' => $this->voucherFormat];

        $org->document_settings = $settings;
        $org->save();

        $this->dispatch('notify', ['message' => 'Document numbering settings saved successfully!']);
    }

    public function render()
    {
        return view('livewire.settings.document-numbering')->layout('components.layouts.app', ['title' => 'Document Numbering']);
    }
}

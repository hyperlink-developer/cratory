<?php

namespace App\Livewire\Settings;

use Livewire\Component;

class DocumentNumbering extends Component
{
    public $invoicePrefix = 'INV';
    public $invoicePattern = '{PREFIX}{SEP}{FY}{SEP}{SEQ}';
    public $invoiceSeparator = '-';

    public $receiptPrefix = 'REC';
    public $receiptPattern = '{PREFIX}{SEP}{FY}{SEP}{SEQ}';
    public $receiptSeparator = '-';

    public $voucherPrefix = 'PAY';
    public $voucherPattern = '{PREFIX}{SEP}{FY}{SEP}{SEQ}';
    public $voucherSeparator = '-';

    private function parseFormat($format)
    {
        $separators = ['-', '/', ' '];
        foreach ($separators as $sep) {
            if (str_contains($format, $sep)) {
                return [
                    'pattern' => str_replace($sep, '{SEP}', $format),
                    'separator' => $sep,
                ];
            }
        }
        return ['pattern' => str_replace('-', '{SEP}', $format), 'separator' => '-']; // Default fallback
    }

    public function mount()
    {
        $org = auth()->user()->currentOrganization;
        $settings = $org->document_settings ?? [];

        $this->invoicePrefix = $settings['invoice']['prefix'] ?? $org->invoice_prefix ?? 'INV';
        $invParsed = $this->parseFormat($settings['invoice']['format'] ?? '{PREFIX}-{FY}-{SEQ}');
        $this->invoicePattern = $invParsed['pattern'];
        $this->invoiceSeparator = $invParsed['separator'];

        $this->receiptPrefix = $settings['receipt']['prefix'] ?? 'REC';
        $recParsed = $this->parseFormat($settings['receipt']['format'] ?? '{PREFIX}-{FY}-{SEQ}');
        $this->receiptPattern = $recParsed['pattern'];
        $this->receiptSeparator = $recParsed['separator'];

        $this->voucherPrefix = $settings['voucher']['prefix'] ?? 'PAY';
        $vouParsed = $this->parseFormat($settings['voucher']['format'] ?? '{PREFIX}-{FY}-{SEQ}');
        $this->voucherPattern = $vouParsed['pattern'];
        $this->voucherSeparator = $vouParsed['separator'];
    }

    public function save()
    {
        $this->validate([
            'invoicePrefix' => 'required|string|max:10',
            'receiptPrefix' => 'required|string|max:10',
            'voucherPrefix' => 'required|string|max:10',
        ]);

        $org = auth()->user()->currentOrganization;
        
        $settings = $org->document_settings ?? [];
        
        $settings['invoice'] = [
            'prefix' => $this->invoicePrefix, 
            'format' => str_replace('{SEP}', $this->invoiceSeparator, $this->invoicePattern)
        ];
        $settings['receipt'] = [
            'prefix' => $this->receiptPrefix, 
            'format' => str_replace('{SEP}', $this->receiptSeparator, $this->receiptPattern)
        ];
        $settings['voucher'] = [
            'prefix' => $this->voucherPrefix, 
            'format' => str_replace('{SEP}', $this->voucherSeparator, $this->voucherPattern)
        ];

        $org->document_settings = $settings;
        $org->save();

        $this->dispatch('notify', ['message' => 'Document numbering settings saved successfully!']);
    }

    public function render()
    {
        return view('livewire.settings.document-numbering')->layout('components.layouts.app', ['title' => 'Document Numbering']);
    }
}

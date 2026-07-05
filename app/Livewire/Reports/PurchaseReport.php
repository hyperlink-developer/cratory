<?php

namespace App\Livewire\Reports;

use App\Models\PurchaseInvoice;
use Livewire\Component;

class PurchaseReport extends Component
{
    public $startDate;
    public $endDate;
    public $status = ''; // all, paid, draft, partial, received

    public function mount()
    {
        $this->startDate = date('Y-m-01'); // Start of current month
        $this->endDate = date('Y-m-t'); // End of current month
    }

    public function getPurchasesProperty()
    {
        $query = PurchaseInvoice::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->whereBetween('purchase_date', [$this->startDate, $this->endDate])
            ->orderBy('purchase_date', 'desc');

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.reports.purchase-report', [
            'purchases' => $this->purchases,
            'totalPurchases' => collect($this->purchases)->sum('grand_total'),
            'totalTax' => collect($this->purchases)->sum('tax_total'),
        ])->layout('components.layouts.app', ['title' => 'Purchase Report']);
    }
}

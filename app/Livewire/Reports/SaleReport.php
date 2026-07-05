<?php

namespace App\Livewire\Reports;

use App\Models\Invoice;
use Livewire\Component;

class SaleReport extends Component
{
    public $startDate;
    public $endDate;
    public $status = ''; // all, paid, draft, sent, partial

    public function mount()
    {
        $this->startDate = date('Y-m-01'); // Start of current month
        $this->endDate = date('Y-m-t'); // End of current month
    }

    public function getSalesProperty()
    {
        $query = Invoice::where('organization_id', auth()->user()->current_organization_id)
            ->with('contact')
            ->whereBetween('invoice_date', [$this->startDate, $this->endDate])
            ->orderBy('invoice_date', 'desc');

        if ($this->status !== '') {
            $query->where('status', $this->status);
        }

        return $query->get();
    }

    public function render()
    {
        return view('livewire.reports.sale-report', [
            'sales' => $this->sales,
            'totalSales' => collect($this->sales)->sum('grand_total'),
            'totalTax' => collect($this->sales)->sum('tax_total'),
        ])->layout('components.layouts.app', ['title' => 'Sales Report']);
    }
}

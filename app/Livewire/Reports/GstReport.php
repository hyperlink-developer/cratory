<?php

namespace App\Livewire\Reports;

use App\Models\GstReportPeriod;
use App\Services\GST\Gstr3bReportService;
use Carbon\Carbon;
use Livewire\Component;

class GstReport extends Component
{
    public $selectedMonth;
    public $selectedYear;
    
    public $months = [];
    public $years = [];

    public function mount()
    {
        $this->selectedMonth = date('m');
        $this->selectedYear = date('Y');
        
        for ($i = 1; $i <= 12; $i++) {
            $this->months[str_pad($i, 2, '0', STR_PAD_LEFT)] = date('F', mktime(0, 0, 0, $i, 1));
        }
        
        $currentYear = date('Y');
        for ($i = $currentYear - 2; $i <= $currentYear; $i++) {
            $this->years[$i] = $i;
        }
    }

    public function getPeriodProperty()
    {
        $startDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        return GstReportPeriod::where('organization_id', auth()->user()->current_organization_id)
            ->where('period_type', 'monthly')
            ->where('period_start', $startDate->format('Y-m-d'))
            ->where('period_end', $endDate->format('Y-m-d'))
            ->first();
    }

    public function generatePeriod()
    {
        $startDate = Carbon::createFromDate($this->selectedYear, $this->selectedMonth, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        GstReportPeriod::create([
            'organization_id' => auth()->user()->current_organization_id,
            'period_type' => 'monthly',
            'period_start' => $startDate->format('Y-m-d'),
            'period_end' => $endDate->format('Y-m-d'),
            'status' => 'draft'
        ]);
        
        $this->dispatch('notify', ['message' => 'GST Period Generated Successfully!', 'type' => 'success']);
    }

    public function render()
    {
        $period = $this->period;
        $summary = null;
        
        if ($period) {
            $service = new Gstr3bReportService($period);
            $summary = $service->getSummary();
        }
        
        return view('livewire.reports.gst-report', [
            'period' => $period,
            'summary' => $summary,
        ])->layout('components.layouts.app', ['title' => 'GST Reports']);
    }
}

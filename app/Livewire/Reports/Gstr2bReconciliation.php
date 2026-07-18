<?php

namespace App\Livewire\Reports;

use App\Models\GstReportPeriod;
use App\Models\Gstr2bReconciliationItem;
use App\Services\Gstr2bMatchingService;
use Livewire\Component;
use Livewire\WithFileUploads;

class Gstr2bReconciliation extends Component
{
    use WithFileUploads;

    public $periodId;
    public $csvFile;
    public $isProcessing = false;

    protected $rules = [
        'periodId' => 'required|exists:gst_report_periods,id',
        'csvFile' => 'required|file|mimes:csv,txt|max:10240', // Max 10MB
    ];

    public function mount()
    {
        $orgId = auth()->user()->current_organization_id;
        $latestPeriod = GstReportPeriod::where('organization_id', $orgId)
            ->latest('period_start')
            ->first();
            
        if ($latestPeriod) {
            $this->periodId = $latestPeriod->id;
        }
    }

    public function processUpload(Gstr2bMatchingService $service)
    {
        $this->validate();

        $this->isProcessing = true;
        
        $period = GstReportPeriod::findOrFail($this->periodId);
        
        // Ensure user owns this period's org
        if ($period->organization_id !== auth()->user()->current_organization_id) {
            abort(403);
        }

        $filePath = $this->csvFile->getRealPath();

        try {
            $service->processCsvUpload($filePath, $period);
            session()->flash('message', 'GSTR-2B CSV processed successfully!');
        } catch (\Exception $e) {
            session()->flash('error', 'Error processing CSV: ' . $e->getMessage());
        }

        // Delete the temporary uploaded file
        @unlink($filePath);
        
        $this->csvFile = null;
        $this->isProcessing = false;
    }

    public function render()
    {
        $orgId = auth()->user()->current_organization_id;
        
        $periods = GstReportPeriod::where('organization_id', $orgId)
            ->orderBy('period_start', 'desc')
            ->get();

        $items = collect();
        if ($this->periodId) {
            $items = Gstr2bReconciliationItem::where('gst_report_period_id', $this->periodId)
                ->with('purchaseInvoice')
                ->get();
        }

        return view('livewire.reports.gstr2b-reconciliation', [
            'periods' => $periods,
            'items' => $items,
        ])->layout('layouts.app');
    }
}

<?php

namespace App\Livewire;

use App\Enums\InvoiceStatus;
use App\Models\Invoice;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Livewire\Component;

class Dashboard extends Component
{
    public string $period = 'month';

    public function setPeriod(string $period): void
    {
        $this->period = $period;
    }

    private function getPeriodDates(): array
    {
        return match ($this->period) {
            'week' => [Carbon::now()->startOfWeek(), Carbon::now()],
            'month' => [Carbon::now()->startOfMonth(), Carbon::now()],
            'quarter' => [Carbon::now()->startOfQuarter(), Carbon::now()],
            'year' => [Carbon::now()->startOfYear(), Carbon::now()],
            default => [Carbon::now()->startOfMonth(), Carbon::now()],
        };
    }

    public function getKpiProperty(): array
    {
        [$start, $end] = $this->getPeriodDates();

        $totalSales = Invoice::whereBetween('invoice_date', [$start, $end])
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('grand_total');

        $totalPurchases = PurchaseInvoice::whereBetween('purchase_date', [$start, $end])
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->sum('grand_total');

        $totalReceivable = Invoice::whereNotIn('status', ['draft', 'cancelled', 'paid'])
            ->sum('balance_due');

        $totalPayable = PurchaseInvoice::whereNotIn('status', ['draft', 'cancelled', 'paid'])
            ->sum('balance_due');

        return [
            'total_sales' => $totalSales,
            'total_purchases' => $totalPurchases,
            'total_receivable' => $totalReceivable,
            'total_payable' => $totalPayable,
        ];
    }

    public function getRecentActivityProperty(): Collection
    {
        $invoices = Invoice::with('contact')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($i) => [
                'type' => 'invoice',
                'label' => $i->invoice_number ?? 'Draft Invoice',
                'contact' => $i->contact?->display_name,
                'amount' => $i->grand_total,
                'status' => $i->status->value,
                'date' => $i->created_at,
                'route' => route('invoices.edit', $i->uuid)
            ]);

        $receipts = Receipt::with('contact')
            ->latest()
            ->take(5)
            ->get()
            ->map(fn ($r) => [
                'type' => 'receipt',
                'label' => $r->receipt_number ?? 'Receipt',
                'contact' => $r->contact?->display_name,
                'amount' => $r->amount,
                'status' => 'paid',
                'date' => $r->created_at,
                'route' => route('receipts.index')
            ]);

        return $invoices->merge($receipts)
            ->sortByDesc('date')
            ->take(8)
            ->values();
    }

    public function getOverdueInvoicesProperty(): Collection
    {
        return Invoice::with('contact')
            ->where('due_date', '<', Carbon::now()->format('Y-m-d'))
            ->where('balance_due', '>', 0)
            ->whereNotIn('status', ['draft', 'cancelled'])
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get();
    }

    public function getLowStockProductsProperty(): Collection
    {
        // Fetch products where current_stock is <= reorder_level
        return Product::whereColumn('current_stock', '<=', 'reorder_level')
            ->where('item_type', 'product')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.dashboard', [
            'kpis' => $this->kpi,
            'recentActivity' => $this->recent_activity,
            'overdueInvoices' => $this->overdue_invoices,
            'lowStock' => $this->low_stock_products
        ])->layout('components.layouts.app', ['title' => 'Dashboard']);
    }
}

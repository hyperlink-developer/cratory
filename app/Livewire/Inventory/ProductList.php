<?php

namespace App\Livewire\Inventory;

use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public string $search = '';
    public string $typeFilter = '';
    public bool $lowStockOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'typeFilter' => ['except' => ''],
        'lowStockOnly' => ['except' => false],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingLowStockOnly()
    {
        $this->resetPage();
    }

    public function deleteProduct(string $uuid)
    {
        $product = Product::where('uuid', $uuid)->firstOrFail();
        $product->delete();
        $this->dispatch('notify', ['message' => 'Item deleted successfully']);
    }

    public function render()
    {
        $products = Product::with(['category', 'taxRate'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->typeFilter, function ($query) {
                $query->where('item_type', $this->typeFilter);
            })
            ->when($this->lowStockOnly, function ($query) {
                $query->where('item_type', 'product')
                      ->whereNotNull('reorder_level')
                      ->whereColumn('current_stock', '<=', 'reorder_level');
            })
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.inventory.product-list', [
            'products' => $products
        ])->layout('components.layouts.app', ['title' => 'Inventory']);
    }
}

<?php

namespace App\Livewire\Inventory;

use App\Enums\ItemType;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\TaxRate;
use Livewire\Component;

class ProductForm extends Component
{
    public ?Product $product = null;

    public string $itemType = 'product';
    public string $name = '';
    public string $sku = '';
    public string $description = '';
    public string $unit = 'pcs';
    public ?int $categoryId = null;
    
    public string $hsnCode = '';
    public string $sacCode = '';
    
    public string $sellingPrice = '0.00';
    public string $purchasePrice = '0.00';
    public ?int $taxRateId = null;
    
    // Inventory tracking
    public string $openingStock = '0.00';
    public string $reorderLevel = '0.00';
    
    // Quick add category support
    public string $newCategoryName = '';
    public bool $showNewCategoryInput = false;

    public function mount(Product $product = null)
    {
        if ($product && $product->exists) {
            $this->product = $product;
            
            $this->itemType = $product->item_type->value;
            $this->name = $product->name;
            $this->sku = $product->sku ?? '';
            $this->description = $product->description ?? '';
            $this->unit = $product->unit ?? 'pcs';
            $this->categoryId = $product->category_id;
            
            $this->hsnCode = $product->hsn_code ?? '';
            $this->sacCode = $product->sac_code ?? '';
            
            $this->sellingPrice = $product->selling_price;
            $this->purchasePrice = $product->purchase_price;
            $this->taxRateId = $product->tax_rate_id;
            
            $this->openingStock = $product->opening_stock ?? '0.00';
            $this->reorderLevel = $product->reorder_level ?? '0.00';
        }
    }

    protected function rules()
    {
        $rules = [
            'itemType' => 'required|string',
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'categoryId' => 'nullable|exists:product_categories,id',
            'taxRateId' => 'nullable|exists:tax_rates,id',
            
            'sellingPrice' => 'required|numeric|min:0',
            'purchasePrice' => 'required|numeric|min:0',
        ];

        if ($this->itemType === 'product') {
            $rules['unit'] = 'nullable|string|max:50';
            $rules['hsnCode'] = 'nullable|string|max:50';
            $rules['openingStock'] = 'required|numeric|min:0';
            $rules['reorderLevel'] = 'nullable|numeric|min:0';
        } else {
            $rules['sacCode'] = 'nullable|string|max:50';
        }

        return $rules;
    }

    public function createCategory()
    {
        $this->validate(['newCategoryName' => 'required|string|max:255']);
        
        $category = ProductCategory::create([
            'organization_id' => auth()->user()->current_organization_id,
            'name' => $this->newCategoryName,
        ]);
        
        $this->categoryId = $category->id;
        $this->newCategoryName = '';
        $this->showNewCategoryInput = false;
        
        $this->dispatch('notify', ['message' => 'Category created!']);
    }

    public function save()
    {
        $this->validate();

        $data = [
            'item_type' => $this->itemType,
            'name' => $this->name,
            'sku' => $this->sku ?: null,
            'description' => $this->description ?: null,
            'category_id' => $this->categoryId,
            'selling_price' => $this->sellingPrice,
            'purchase_price' => $this->purchasePrice,
            'tax_rate_id' => $this->taxRateId,
        ];

        if ($this->itemType === 'product') {
            $data['unit'] = $this->unit ?: 'pcs';
            $data['hsn_code'] = $this->hsnCode ?: null;
            $data['sac_code'] = null; // Clear SAC for products
            $data['opening_stock'] = $this->openingStock;
            $data['reorder_level'] = $this->reorderLevel ?: null;
            
            // If new product, current stock = opening stock
            if (!$this->product) {
                $data['current_stock'] = $this->openingStock;
            }
        } else {
            // Service specific clears
            $data['unit'] = null;
            $data['hsn_code'] = null;
            $data['sac_code'] = $this->sacCode ?: null;
            $data['opening_stock'] = 0;
            $data['current_stock'] = 0;
            $data['reorder_level'] = null;
        }

        if ($this->product && $this->product->exists) {
            $this->product->update($data);
        } else {
            $data['organization_id'] = auth()->user()->current_organization_id;
            Product::create($data);
        }

        $this->redirect(route('inventory.index'), navigate: true);
    }

    public function getCategoriesProperty()
    {
        return ProductCategory::orderBy('name')->get();
    }

    public function getTaxRatesProperty()
    {
        return TaxRate::active()->orderBy('percentage')->get();
    }
    
    public function getItemTypesProperty()
    {
        return array_map(fn($t) => ['value' => $t->value, 'label' => $t->label()], ItemType::cases());
    }

    public function render()
    {
        return view('livewire.inventory.product-form')->layout('components.layouts.app', [
            'title' => $this->product && $this->product->exists ? 'Edit Item' : 'New Item'
        ]);
    }
}

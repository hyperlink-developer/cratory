<?php

namespace App\Models;

use App\Enums\ItemType;
use App\Traits\BelongsToOrganization;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use BelongsToOrganization, HasUuid, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'item_type',
        'category_id',
        'name',
        'sku',
        'description',
        'unit_of_measure_id',
        'hsn_code',
        'sac_code',
        'purchase_price',
        'selling_price',
        'tax_rate_id',
        'opening_stock',
        'current_stock',
        'reorder_level',
        'image_path',
        'is_active',
        'is_batch_tracked',
        'low_stock_threshold',
    ];

    protected function casts(): array
    {
        return [
            'item_type' => ItemType::class,
            'purchase_price' => 'decimal:2',
            'selling_price' => 'decimal:2',
            'opening_stock' => 'decimal:2',
            'current_stock' => 'decimal:2',
            'reorder_level' => 'decimal:2',
            'is_active' => 'boolean',
            'is_batch_tracked' => 'boolean',
            'low_stock_threshold' => 'decimal:2',
        ];
    }

    // Relationships

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class);
    }

    public function taxRate(): BelongsTo
    {
        return $this->belongsTo(TaxRate::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function batches(): HasMany
    {
        return $this->hasMany(ProductBatch::class);
    }

    // Scopes

    public function scopeProducts($query)
    {
        return $query->where('item_type', ItemType::Product);
    }

    public function scopeServices($query)
    {
        return $query->where('item_type', ItemType::Service);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('item_type', ItemType::Product)
            ->whereNotNull('reorder_level')
            ->whereColumn('current_stock', '<=', 'reorder_level');
    }

    // Helpers

    public function isProduct(): bool
    {
        return $this->item_type === ItemType::Product;
    }

    public function isService(): bool
    {
        return $this->item_type === ItemType::Service;
    }

    public function isLowStock(): bool
    {
        return $this->isProduct()
            && $this->reorder_level !== null
            && $this->current_stock <= $this->reorder_level;
    }
}

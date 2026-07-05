<?php

namespace App\Enums;

enum StockMovementType: string
{
    case PurchaseIn = 'purchase_in';
    case SaleOut = 'sale_out';
    case AdjustmentIn = 'adjustment_in';
    case AdjustmentOut = 'adjustment_out';
    case Opening = 'opening';

    public function label(): string
    {
        return match ($this) {
            self::PurchaseIn => 'Purchase In',
            self::SaleOut => 'Sale Out',
            self::AdjustmentIn => 'Adjustment In',
            self::AdjustmentOut => 'Adjustment Out',
            self::Opening => 'Opening Stock',
        };
    }

    public function isInward(): bool
    {
        return in_array($this, [self::PurchaseIn, self::AdjustmentIn, self::Opening]);
    }
}

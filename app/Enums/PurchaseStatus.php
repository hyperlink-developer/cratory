<?php

namespace App\Enums;

enum PurchaseStatus: string
{
    case Draft = 'draft';
    case Received = 'received';
    case Partial = 'partial';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Received => 'Received',
            self::Partial => 'Partial',
            self::Paid => 'Paid',
            self::Cancelled => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Draft => 'gray',
            self::Received => 'blue',
            self::Partial => 'amber',
            self::Paid => 'green',
            self::Cancelled => 'red',
        };
    }
}

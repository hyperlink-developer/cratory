<?php

namespace App\Enums;

enum BusinessCategory: string
{
    case Product = 'product';
    case Service = 'service';
    case Both = 'both';

    public function label(): string
    {
        return match ($this) {
            self::Product => 'Product Based',
            self::Service => 'Service Based',
            self::Both => 'Product & Service',
        };
    }

    public function showsPurchases(): bool
    {
        return $this !== self::Service;
    }

    public function showsInventory(): bool
    {
        return $this !== self::Service;
    }
}

<?php

namespace App\Enums;

enum InvoiceBasis: string
{
    case Cash = 'cash';
    case Credit = 'credit';

    public function label(): string
    {
        return match ($this) {
            self::Cash => 'Cash',
            self::Credit => 'Credit',
        };
    }
}

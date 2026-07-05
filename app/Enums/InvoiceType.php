<?php

namespace App\Enums;

enum InvoiceType: string
{
    case Sales = 'sales';
    case Service = 'service';

    public function label(): string
    {
        return match ($this) {
            self::Sales => 'Sales Invoice',
            self::Service => 'Service Invoice',
        };
    }

    public function documentCode(): string
    {
        return match ($this) {
            self::Sales => 'INV',
            self::Service => 'SRV',
        };
    }
}

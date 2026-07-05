<?php

namespace App\Enums;

enum OrganizationType: string
{
    case Proprietorship = 'proprietorship';
    case Partnership = 'partnership';
    case PvtLtd = 'pvt_ltd';
    case Llp = 'llp';
    case Huf = 'huf';
    case Individual = 'individual';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Proprietorship => 'Proprietorship',
            self::Partnership => 'Partnership',
            self::PvtLtd => 'Private Limited',
            self::Llp => 'LLP',
            self::Huf => 'HUF',
            self::Individual => 'Individual',
            self::Other => 'Other',
        };
    }
}

<?php

namespace App\Enums;

enum OrgUserRole: string
{
    case Commander = 'commander';
    case OrgAdmin = 'org_admin';
    case Accountant = 'accountant';
    case Staff = 'staff';

    public function label(): string
    {
        return match ($this) {
            self::Commander => 'Commander',
            self::OrgAdmin => 'Org Admin',
            self::Accountant => 'Accountant',
            self::Staff => 'Staff',
        };
    }
}

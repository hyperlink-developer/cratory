<?php

namespace App\Enums;

enum OrgUserStatus: string
{
    case Active = 'active';
    case Invited = 'invited';
    case Suspended = 'suspended';
}

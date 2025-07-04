<?php

declare(strict_types=1);

namespace App\Enums;

enum TeamMemberStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
}

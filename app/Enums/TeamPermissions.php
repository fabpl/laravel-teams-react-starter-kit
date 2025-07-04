<?php

declare(strict_types=1);

namespace App\Enums;

enum TeamPermissions: string
{
    case TEAM_UPDATE = 'team.update';
    case TEAM_DELETE = 'team.delete';

    case TEAM_MEMBER_CREATE = 'team-member.create';
    case TEAM_MEMBER_UPDATE = 'team-member.update';
    case TEAM_MEMBER_DELETE = 'team-member.delete';
}

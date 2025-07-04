<?php

declare(strict_types=1);

namespace App\Enums;

enum TeamRoles: string
{
    case ADMIN = 'admin';
    case COLLABORATOR = 'collaborator';

    /**
     * @return string[]
     */
    public static function values(): array
    {
        return array_map(fn (TeamRoles $role): string => $role->value, self::cases());
    }

    /**
     * @return TeamPermissions[]
     */
    public function permissions(): array
    {
        return match ($this) {
            self::ADMIN => [
                TeamPermissions::TEAM_UPDATE,
                TeamPermissions::TEAM_DELETE,
                TeamPermissions::TEAM_MEMBER_CREATE,
                TeamPermissions::TEAM_MEMBER_UPDATE,
                TeamPermissions::TEAM_MEMBER_DELETE,
            ],
            self::COLLABORATOR => [
                TeamPermissions::TEAM_UPDATE,
                TeamPermissions::TEAM_MEMBER_CREATE,
                TeamPermissions::TEAM_MEMBER_UPDATE,
                TeamPermissions::TEAM_MEMBER_DELETE,
            ],
        };
    }
}

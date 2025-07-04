<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\TeamPermissions;
use App\Models\Team;
use App\Models\User;

final class TeamPolicy
{
    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Team $team): bool
    {
        return $user->hasTeamPermission($team, TeamPermissions::TEAM_UPDATE);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function delete(User $user, Team $team): bool
    {
        return $user->hasTeamPermission($team, TeamPermissions::TEAM_DELETE);
    }

    /**
     * Determine whether the user can add team members.
     */
    public function addTeamMember(User $user, Team $team): bool
    {
        return $user->hasTeamPermission($team, TeamPermissions::TEAM_MEMBER_CREATE);
    }

    /**
     * Determine whether the user can update team member permissions.
     */
    public function updateTeamMember(User $user, Team $team): bool
    {
        return $user->hasTeamPermission($team, TeamPermissions::TEAM_MEMBER_UPDATE);
    }

    /**
     * Determine whether the user can delete team members.
     */
    public function deleteTeamMember(User $user, Team $team): bool
    {
        return $user->hasTeamPermission($team, TeamPermissions::TEAM_MEMBER_DELETE);
    }
}

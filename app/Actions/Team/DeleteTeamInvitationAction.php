<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Throwable;

final class DeleteTeamInvitationAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, TeamInvitation $invitation): void
    {
        $this->authorize($user, $invitation->team);

        $invitation->delete();
    }

    /**
     * @throws AuthorizationException
     */
    private function authorize(User $user, Team $team): void
    {
        if ($user->cannot('deleteTeamMember', $team)) {
            throw new AuthorizationException();
        }
    }
}

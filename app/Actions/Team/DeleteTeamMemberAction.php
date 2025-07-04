<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

final class DeleteTeamMemberAction
{
    /**
     * @throws AuthorizationException
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(User $user, Team $team, User $member): void
    {
        $this->authorize($user, $team);

        $this->validate($team, $member);

        DB::transaction(function () use ($team, $member): void {
            $team->users()->where('current_team_id', $team->id)->update(['current_team_id' => null]);

            $team->users()->detach($member);
        });
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

    /**
     * @throws ValidationException
     */
    private function validate(Team $team, User $user): void
    {
        if ($team->users()->wherePivot('role', TeamRoles::ADMIN)->whereKeyNot($user->id)->doesntExist()) {
            throw ValidationException::withMessages([
                'team' => [__('Team must have at least one admin member.')],
            ]);
        }
    }
}

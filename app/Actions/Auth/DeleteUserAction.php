<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Actions\Team\DeleteTeamAction;
use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class DeleteUserAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            $user->teams()->wherePivot('role', TeamRoles::ADMIN)->each(function (Team $team) use ($user): void {
                if (! $team->users()->wherePivot('role', TeamRoles::ADMIN)->whereKeyNot($user->id)->exists()) {
                    $deleteTeam = app(DeleteTeamAction::class);

                    $deleteTeam->handle($user, $team);
                }
            });

            $user->teams()->detach();

            $user->delete();
        });
    }
}

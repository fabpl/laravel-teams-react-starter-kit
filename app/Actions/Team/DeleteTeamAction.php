<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\DB;
use Throwable;

final class DeleteTeamAction
{
    /**
     * @throws AuthorizationException
     * @throws Throwable
     */
    public function handle(User $user, Team $team): void
    {
        $this->authorize($user, $team);

        DB::transaction(function () use ($team): void {
            $team->invitations()->delete();

            $team->users()->where('current_team_id', $team->id)->update(['current_team_id' => null]);

            $team->users()->detach();

            $team->delete();
        });
    }

    /**
     * @throws AuthorizationException
     */
    private function authorize(User $user, Team $team): void
    {
        if ($user->cannot('delete', $team)) {
            throw new AuthorizationException();
        }
    }
}

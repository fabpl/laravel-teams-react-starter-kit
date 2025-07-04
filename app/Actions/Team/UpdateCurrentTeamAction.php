<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Throwable;

final class UpdateCurrentTeamAction
{
    /**
     * @throws Throwable
     */
    public function handle(User $user, Team $team): bool
    {
        if (! $user->belongsToTeam($team)) {
            return false;
        }

        return DB::transaction(function () use ($user, $team): true {
            $user->update(['current_team_id' => $team->id]);

            $user->setRelation('currentTeam', $team);

            return true;
        });
    }
}

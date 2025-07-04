<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Actions\Team\UpdateCurrentTeamAction;
use App\Models\Team;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

final class HandleCurrentTeam
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     *
     * @throws Throwable
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User $user */
        $user = $request->user();

        if (blank($user->current_team_id)) {
            /** @var ?Team $team */
            $team = $user->teams->first();

            if (blank($team)) {
                return to_route('teams.create');
            }

            /** @var UpdateCurrentTeamAction $action */
            $action = app(UpdateCurrentTeamAction::class);

            $action->handle($user, $team);
        }

        return $next($request);
    }
}

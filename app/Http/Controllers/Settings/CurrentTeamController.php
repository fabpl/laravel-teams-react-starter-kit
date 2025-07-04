<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Team\UpdateCurrentTeamAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

final class CurrentTeamController extends Controller
{
    /**
     * @throws Throwable
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, UpdateCurrentTeamAction $updateCurrentTeam): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $team = $user->teams->find($request->integer('team_id'));

        abort_if(blank($team) || $updateCurrentTeam->handle($user, $team) === false, 403);

        return to_route('dashboard');
    }
}

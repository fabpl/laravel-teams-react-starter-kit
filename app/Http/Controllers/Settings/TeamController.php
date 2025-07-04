<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Team\CreateTeamAction;
use App\Actions\Team\DeleteTeamAction;
use App\Actions\Team\UpdateTeamAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\TeamDestroyRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class TeamController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('settings/teams/create');
    }

    /**
     * @throws Throwable
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, CreateTeamAction $createTeam): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var array<string, mixed> $input */
        $input = $request->all();

        $createTeam->handle($user, $input);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit(Request $request, Team $team): Response
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        Gate::forUser($user)->authorize('update', $team);

        return Inertia::render('settings/teams/edit', [
            'team' => $team,
            'members' => $team->members()->orderBy('email')->paginate(),
        ]);
    }

    /**
     * @throws Throwable
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Team $team, UpdateTeamAction $updateTeam): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var array<string, mixed> $input */
        $input = $request->all();

        $updateTeam->handle($user, $team, $input);

        return to_route('teams.edit', [
            'team' => $team,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function destroy(TeamDestroyRequest $request, Team $team, DeleteTeamAction $deleteTeam): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $deleteTeam->handle($user, $team);

        return to_route('dashboard');
    }
}

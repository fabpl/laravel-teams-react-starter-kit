<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Team\DeleteTeamMemberAction;
use App\Actions\Team\UpdateTeamMemberAction;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

final class TeamMemberController extends Controller
{
    /**
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function index(Request $request, Team $team): Response
    {
        /** @var User $user */
        $user = $request->user();

        Gate::forUser($user)->authorize('update', $team);

        return Inertia::render('settings/teams/members', [
            'team' => $team,
            'members' => $team->members()->orderBy('email')->paginate(),
        ]);
    }

    /**
     * @throws Throwable
     */
    public function update(Request $request, Team $team, User $member, UpdateTeamMemberAction $updateTeamMember): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var array<string, mixed> $input */
        $input = $request->all();

        $updateTeamMember->handle($user, $team, $member, $input);

        return to_route('teams.members', [
            'team' => $team,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function destroy(Request $request, Team $team, User $member, DeleteTeamMemberAction $deleteTeamMember): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        $deleteTeamMember->handle($user, $team, $member);

        return to_route('teams.members', [
            'team' => $team,
        ]);
    }
}

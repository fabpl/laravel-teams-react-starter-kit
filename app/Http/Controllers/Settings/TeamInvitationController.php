<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Team\CreateTeamInvitationAction;
use App\Actions\Team\DeleteTeamInvitationAction;
use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

final class TeamInvitationController extends Controller
{
    /**
     * @throws Throwable
     */
    public function store(Request $request, Team $team, CreateTeamInvitationAction $inviteTeamMember): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        /** @var array<string, mixed> $input */
        $input = $request->all();

        $inviteTeamMember->handle($user, $team, $input);

        return to_route('teams.edit', [
            'team' => $team,
        ]);
    }

    /**
     * @throws Throwable
     */
    public function destroy(Request $request, TeamInvitation $invitation, DeleteTeamInvitationAction $deleteTeamInvitation): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $deleteTeamInvitation->handle($user, $invitation);

        return to_route('teams.edit', [
            'team' => $invitation->team,
        ]);
    }
}

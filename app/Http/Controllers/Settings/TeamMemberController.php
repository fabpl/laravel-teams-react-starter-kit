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
use Throwable;

final class TeamMemberController extends Controller
{
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

        return to_route('teams.edit', [
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

        return to_route('teams.edit', [
            'team' => $team,
        ]);
    }
}

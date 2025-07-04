<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\TeamRoles;
use App\Mail\TeamInvitationMail;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

final class CreateTeamInvitationAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(User $user, Team $team, array $input): TeamInvitation
    {
        $this->authorize($user, $team);

        /** @var array{email: string, role: string} $validated */
        $validated = $this->validate($team, $input);

        /** @var TeamInvitation $invitation */
        $invitation = $team->invitations()->create($validated);

        Mail::to($validated['email'])->send(new TeamInvitationMail($invitation));

        return $invitation;
    }

    /**
     * @throws AuthorizationException
     */
    private function authorize(User $user, Team $team): void
    {
        if ($user->cannot('addTeamMember', $team)) {
            throw new AuthorizationException();
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{email: string, role: string}
     *
     * @throws ValidationException
     */
    private function validate(Team $team, array $input): array
    {
        /** @var array{email: string, role: string} $validated */
        $validated = Validator::make($input, [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(TeamInvitation::class)->where(function (Builder $query) use ($team): void {
                $query->where('team_id', $team->id);
            })],
            'role' => ['required', Rule::enum(TeamRoles::class)],
        ])->validate();

        if ($team->users()->where('email', $input['email'])->exists()) {
            throw ValidationException::withMessages([
                'email' => __('The email address is already associated with a team member.'),
            ]);
        }

        return $validated;
    }
}

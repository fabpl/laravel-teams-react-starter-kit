<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\TeamRoles;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

final class AcceptTeamInvitationAction
{
    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(TeamInvitation $invitation): void
    {
        $this->validate($invitation);

        /** @var User $member */
        $member = User::where('email', $invitation->email)->first();

        DB::transaction(function () use ($member, $invitation): void {
            $invitation->team->users()->attach($member, [
                'role' => $invitation->role,
            ]);

            $member->currentTeam()->associate($invitation->team);

            $invitation->delete();
        });
    }

    /**
     * @throws ValidationException
     */
    private function validate(TeamInvitation $invitation): void
    {
        Validator::make([
            'email' => $invitation->email,
            'role' => $invitation->role,
        ], [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'exists:users,email'],
            'role' => ['required', Rule::enum(TeamRoles::class)],
        ])->validate();

        if ($invitation->team->users()->where('email', $invitation->email)->exists()) {
            throw ValidationException::withMessages([
                'email' => __('The email address is already associated with a team member.'),
            ]);
        }
    }
}

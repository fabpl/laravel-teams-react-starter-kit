<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

final class UpdateTeamMemberAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws AuthorizationException
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(User $user, Team $team, User $member, array $input): void
    {
        $this->authorize($user, $team);

        /** @var array{role: string} $validated */
        $validated = $this->validate($input);

        $team->users()->updateExistingPivot($member->id, $validated);
    }

    /**
     * @throws AuthorizationException
     */
    private function authorize(User $user, Team $team): void
    {
        if ($user->cannot('updateTeamMember', $team)) {
            throw new AuthorizationException();
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{role: string}
     *
     * @throws ValidationException
     */
    private function validate(array $input): array
    {
        /** @var array{role: string} $validated */
        $validated = Validator::make($input, [
            'role' => ['required', Rule::enum(TeamRoles::class)],
        ])->validate();

        return $validated;
    }
}

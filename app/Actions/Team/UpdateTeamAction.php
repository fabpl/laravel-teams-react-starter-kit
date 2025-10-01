<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

final class UpdateTeamAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws AuthorizationException
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(User $user, Team $team, array $input): void
    {
        $this->authorize($user, $team);

        /** @var array{name: string} $validated */
        $validated = $this->validate($input);

        $team->fill($validated);

        $team->save();
    }

    /**
     * @throws AuthorizationException
     */
    private function authorize(User $user, Team $team): void
    {
        if ($user->cannot('update', $team)) {
            throw new AuthorizationException();
        }
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{name: string}
     *
     * @throws ValidationException
     */
    private function validate(array $input): array
    {
        /** @var array{name: string} $validated */
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validate();

        return $validated;
    }
}

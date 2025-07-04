<?php

declare(strict_types=1);

namespace App\Actions\Team;

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

final class CreateTeamAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws Throwable
     * @throws ValidationException
     */
    public function handle(User $user, array $input): Team
    {
        /** @var array{name: string} $validated */
        $validated = $this->validate($input);

        return DB::transaction(function () use ($user, $validated) {
            $team = Team::create([
                'name' => $validated['name'],
            ]);

            $team->users()->attach($user, [
                'role' => TeamRoles::ADMIN,
            ]);

            $user->currentTeam()->associate($team);

            /** @var UpdateCurrentTeamAction $switchCurrentTeam */
            $switchCurrentTeam = app(UpdateCurrentTeamAction::class);

            $switchCurrentTeam->handle($user, $team);

            return $team;
        });
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

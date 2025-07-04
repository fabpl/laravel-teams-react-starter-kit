<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

final class UpdateUserProfileAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(User $user, array $input): void
    {
        /** @var array{name: string, email: string} $validated */
        $validated = $this->validate($user, $input);

        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{name: string, email: string}
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validate(User $user, array $input): array
    {
        /** @var array{name: string, email: string} $validated */
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($user->id),
            ],
        ])->validate();

        return $validated;
    }
}

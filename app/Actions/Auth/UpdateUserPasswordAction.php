<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

final class UpdateUserPasswordAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(User $user, array $input): void
    {
        /** @var array{password: string} $validated */
        $validated = $this->validate($input);

        $validated['password'] = Hash::make($validated['password']);

        $user->fill($validated);

        $user->save();
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{password: string}
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validate(array $input): array
    {
        /** @var array{password: string} $validated */
        $validated = Validator::make($input, [
            'password' => ['required', 'string', Password::defaults()],
        ])->validate();

        return $validated;
    }
}

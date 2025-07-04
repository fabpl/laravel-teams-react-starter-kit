<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

final class CreateUserAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws ValidationException
     */
    public function handle(array $input): User
    {
        /** @var array{name: string, email: string, password: string, password_confirmation: string} $validated */
        $validated = $this->validate($input);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));

        return $user;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{name: string, email: string, password: string, password_confirmation: string}
     *
     * @throws ValidationException
     */
    private function validate(array $input): array
    {
        /** @var array{name: string, email: string, password: string, password_confirmation: string} $validated */
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::defaults(), 'confirmed'],
        ])->validate();

        return $validated;
    }
}

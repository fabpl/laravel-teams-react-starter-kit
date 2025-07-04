<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;

final class ResetUserPasswordAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(array $input): string
    {
        /** @var array{token: string, email: string, password: string, password_confirmation: string} $validated */
        $validated = $this->validate($input);

        /** @var string $status */
        $status = Password::reset($validated, function (User $user) use ($validated): void {
            $user->forceFill([
                'password' => Hash::make($validated['password']),
                'remember_token' => Str::random(60),
            ])->save();

            event(new PasswordReset($user));
        });

        return $status;
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{token: string, email: string, password: string, password_confirmation: string}
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validate(array $input): array
    {
        /** @var array{token: string, email: string, password: string, password_confirmation: string} $validated */
        $validated = Validator::make($input, [
            'token' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', PasswordRule::defaults(), 'confirmed'],
        ])->validate();

        return $validated;
    }
}

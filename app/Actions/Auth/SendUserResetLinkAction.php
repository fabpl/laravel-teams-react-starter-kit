<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

final class SendUserResetLinkAction
{
    /**
     * @param  array<string, mixed>  $input
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function handle(array $input): string
    {
        /** @var array{email: string} $validated */
        $validated = $this->validate($input);

        return Password::sendResetLink($validated);
    }

    /**
     * @param  array<string, mixed>  $input
     * @return array{email: string}
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    private function validate(array $input): array
    {
        /** @var array{email: string} $validated */
        $validated = Validator::make($input, [
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ])->validate();

        return $validated;
    }
}

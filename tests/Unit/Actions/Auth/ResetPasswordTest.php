<?php

declare(strict_types=1);

use App\Actions\Auth\ResetUserPasswordAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('password can be reset with valid token', function () {
    $user = User::factory()->create();

    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $action = app(ResetUserPasswordAction::class);

    $status = $action->handle([
        'token' => $token,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertTrue($status === Password::PASSWORD_RESET);
});

test('password can not be reset with invalid token', function () {
    $user = User::factory()->create();

    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $action = app(ResetUserPasswordAction::class);

    $status = $action->handle([
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertTrue($status === Password::INVALID_TOKEN);
});

test('password can not be reset with invalid field', function (string $field) {
    $user = User::factory()->create();

    $token = Str::random(64);

    DB::table('password_reset_tokens')->insert([
        'email' => $user->email,
        'token' => Hash::make($token),
        'created_at' => now(),
    ]);

    $payload = [
        'token' => $token,
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $payload[$field] = '';

    $action = app(ResetUserPasswordAction::class);

    $action->handle($payload);
})->with(['token', 'email', 'password'])->throws(ValidationException::class);

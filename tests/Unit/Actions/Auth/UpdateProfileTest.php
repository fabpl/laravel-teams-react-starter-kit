<?php

declare(strict_types=1);

use App\Actions\Auth\UpdateUserProfileAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('profile information can be updated', function () {
    $user = User::factory()->create();

    $action = app(UpdateUserProfileAction::class);

    $action->handle($user, [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);

    $user->refresh();

    $this->assertEquals('Test User', $user->name);
    $this->assertEquals('test@example.com', $user->email);
    $this->assertNull($user->email_verified_at);
});

test('users can not update profile information with invalid field', function (string $field) {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
    ];

    $payload[$field] = '';

    $action = app(UpdateUserProfileAction::class);

    $action->handle($user, $payload);
})->with(['name', 'email'])->throws(ValidationException::class);

test('email verification status is unchanged when the email address is unchanged', function () {
    $user = User::factory()->create();

    $action = app(UpdateUserProfileAction::class);

    $action->handle($user, [
        'name' => 'Test User',
        'email' => $user->email,
    ]);

    $user->refresh();

    $this->assertNotNull($user->email_verified_at);
});

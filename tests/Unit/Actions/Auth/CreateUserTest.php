<?php

declare(strict_types=1);

use App\Actions\Auth\CreateUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('new users can register', function () {
    $action = app(CreateUserAction::class);

    $user = $action->handle([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertInstanceOf(User::class, $user);
    $this->assertEquals('Test User', $user->name);
    $this->assertEquals('test@example.com', $user->email);
    $this->assertTrue(Hash::check('password', $user->password));

    $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
});

test('users can not register with invalid field', function (string $field) {
    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $payload[$field] = '';

    $action = app(CreateUserAction::class);

    $action->handle($payload);
})->with(['name', 'email', 'password'])->throws(ValidationException::class);

test('users can not register with existing email', function () {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test User',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $action = app(CreateUserAction::class);

    $action->handle($payload);
})->throws(ValidationException::class);

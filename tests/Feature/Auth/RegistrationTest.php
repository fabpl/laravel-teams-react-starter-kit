<?php

declare(strict_types=1);

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('authenticated users are redirected to the dashboard page', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->get('/register');

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not register with invalid field', function (string $field) {
    $payload = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $payload[$field] = '';

    $response = $this->post('/register', $payload);

    $response->assertSessionHasErrors([$field]);

    $this->assertGuest();
})->with(['name', 'email', 'password']);

test('users can not register with existing email', function () {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test User',
        'email' => $user->email,
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    $response = $this->post('/register', $payload);

    $response->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

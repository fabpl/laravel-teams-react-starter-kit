<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Event;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('authenticated users are redirected to the dashboard page', function () {
    $this->actingAs(User::factory()->create());

    $response = $this->get('/login');

    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('users can not authenticate with invalid field', function (string $field) {
    $user = User::factory()->create();

    $payload = [
        'email' => $user->email,
        'password' => 'password',
    ];

    $payload[$field] = '';

    $response = $this->post('/login', $payload);

    $response->assertSessionHasErrors([$field]);

    $this->assertGuest();
})->with(['email', 'password']);

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors(['email']);

    $this->assertGuest();
});

test('attempts are limited', function () {
    Event::fake();

    $user = User::factory()->create();

    foreach (range(0, 5) as $_) {
        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);

        $this->assertGuest();
    }

    Event::assertDispatched(Lockout::class);
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $response->assertRedirect('/');

    $this->assertGuest();
});

<?php

declare(strict_types=1);

use App\Models\User;

test('team create page is displayed', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings/teams/create');

    $response->assertOk();
});

test('guests are redirected to the login page', function () {
    $this->get('/settings/teams/create')->assertRedirect('/login');
});

test('team can be created', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->post('/settings/teams', [
            'name' => 'Test Team',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $user->refresh();

    expect($user->currentTeam->name)->toBe('Test Team');
});

test('teams can not created with invalid field', function (string $field) {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test Team',
    ];

    $payload[$field] = '';

    $response = $this
        ->actingAs($user)
        ->post('/settings/teams', $payload);

    $response->assertSessionHasErrors([$field]);
})->with(['name']);

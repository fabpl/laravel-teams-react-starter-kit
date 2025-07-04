<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

test('team edit page is displayed', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->get('/settings/teams/'.$team->id.'/edit');

    $response->assertOk();
});

test('guests are redirected to the login page', function () {
    $team = Team::factory()->create();

    $this->get('/settings/teams/'.$team->id.'/edit')->assertRedirect('/login');
});

test('no-member can not access to edit page', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();

    $response = $this
        ->actingAs($user)
        ->get('/settings/teams/'.$team->id.'/edit');

    $response->assertForbidden();
});

test('team can be updated', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->patch('/settings/teams/'.$team->id, [
            'name' => 'Test Team',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    $user->refresh();

    expect($user->currentTeam->name)->toBe('Test Team');
});

test('team can not updated with invalid field', function (string $field) {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $payload = [
        'name' => 'Test Team',
    ];

    $payload[$field] = '';

    $response = $this
        ->actingAs($user)
        ->patch('/settings/teams/'.$team->id, $payload);

    $response->assertSessionHasErrors([$field]);
})->with(['name']);

test('team can be deleted', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->delete('/settings/teams/'.$team->id, [
            'password' => 'password',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    expect($team->fresh())->toBeNull();
});

test('correct password must be provided to delete team', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->from('/settings/teams/'.$team->id.'/edit')
        ->delete('/settings/teams/'.$team->id, [
            'password' => 'wrong-password',
        ]);

    $response
        ->assertSessionHasErrors('password')
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    expect($team->fresh())->not->toBeNull();
});

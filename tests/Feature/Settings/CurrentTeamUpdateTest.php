<?php

declare(strict_types=1);

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;

test('current team can be updated', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();
    $team->users()->attach($user, [
        'role' => TeamRoles::COLLABORATOR,
    ]);

    $response = $this
        ->actingAs($user)
        ->put('/settings/current-team', [
            'team_id' => $team->id,
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $user->refresh();

    expect($user->current_team_id)->toBe($team->id);
});

test('user must belongs to team to switch it', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();

    $response = $this
        ->actingAs($user)
        ->put('/settings/current-team', [
            'team_id' => $team->id,
        ]);

    $response->assertForbidden();
});

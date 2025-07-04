<?php

declare(strict_types=1);

use App\Actions\Team\UpdateTeamAction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('team can be updated', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $action = app(UpdateTeamAction::class);

    $action->handle($user, $team, [
        'name' => 'Test Team',
    ]);

    $team->refresh();

    $this->assertEquals('Test Team', $team->name);
});

test('team can not be updated with invalid field', function (string $field) {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $payload = [
        'name' => 'Test Team',
    ];

    $payload[$field] = '';

    $action = app(UpdateTeamAction::class);

    $action->handle($user, $team, $payload);
})->with(['name'])->throws(ValidationException::class);

test('non-member can not update team', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();

    $action = app(UpdateTeamAction::class);

    $action->handle($user, $team, [
        'name' => 'Test Team',
    ]);
})->throws(AuthorizationException::class);

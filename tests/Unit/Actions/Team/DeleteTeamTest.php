<?php

declare(strict_types=1);

use App\Actions\Team\DeleteTeamAction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('team can be deleted', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $action = app(DeleteTeamAction::class);

    $action->handle($user, $team);

    $this->assertNull($team->fresh());
});

test('collaborator can not delete team', function () {
    $user = User::factory()->create();

    $team = Team::factory()->create();
    $team->users()->attach($user, [
        'role' => 'collaborator',
    ]);

    $action = app(DeleteTeamAction::class);

    $action->handle($user, $team);
})->throws(AuthorizationException::class);

test('non-member can not delete team', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();

    $action = app(DeleteTeamAction::class);

    $action->handle($user, $team);
})->throws(AuthorizationException::class);

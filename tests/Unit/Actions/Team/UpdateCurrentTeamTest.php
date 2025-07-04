<?php

declare(strict_types=1);

use App\Actions\Team\UpdateCurrentTeamAction;
use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can switch to team', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();
    $user->teams()->attach($team, ['role' => TeamRoles::COLLABORATOR]);

    $action = app(UpdateCurrentTeamAction::class);

    $status = $action->handle($user, $team);

    $this->assertTrue($status);

    $this->assertTrue($user->currentTeam->is($team));
});

test('user must belongs to team to switch it', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();

    $action = app(UpdateCurrentTeamAction::class);

    $status = $action->handle($user, $team);

    $this->assertFalse($status);
});

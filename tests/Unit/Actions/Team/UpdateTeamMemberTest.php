<?php

declare(strict_types=1);

use App\Actions\Team\UpdateTeamMemberAction;
use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('member can be updated', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var User $member */
    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $action = app(UpdateTeamMemberAction::class);

    $action->handle($user, $team, $member, [
        'role' => 'admin',
    ]);

    /** @var User $member */
    $member = $team->users()->where('email', $member->email)->first();

    $this->assertEquals($member->membership->role, TeamRoles::ADMIN);
});

test('only members can updated member', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = Team::factory()->create();

    /** @var User $member */
    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $action = app(UpdateTeamMemberAction::class);

    $action->handle($user, $team, $member, [
        'role' => 'admin',
    ]);
})->throws(AuthorizationException::class);

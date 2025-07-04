<?php

declare(strict_types=1);

use App\Actions\Team\DeleteTeamMemberAction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('member can be deleted', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var User $member */
    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $action = app(DeleteTeamMemberAction::class);

    $action->handle($user, $team, $member);

    $this->assertFalse($team->users()->where('email', $member->email)->exists());
});

test('only members can delete member', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = Team::factory()->create();

    /** @var User $member */
    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $action = app(DeleteTeamMemberAction::class);

    $action->handle($user, $team, $member);
})->throws(AuthorizationException::class);

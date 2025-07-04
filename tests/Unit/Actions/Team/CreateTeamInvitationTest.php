<?php

declare(strict_types=1);

use App\Actions\Team\CreateTeamInvitationAction;
use App\Mail\TeamInvitationMail;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can be invited', function () {
    Mail::fake();

    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $action = app(CreateTeamInvitationAction::class);

    $invitation = $action->handle($user, $team, [
        'email' => 'test@example.com',
        'role' => 'collaborator',
    ]);

    Mail::assertQueued(TeamInvitationMail::class);

    $this->assertInstanceOf(TeamInvitation::class, $invitation);
    $this->assertEquals('test@example.com', $invitation->email);
    $this->assertEquals('collaborator', $invitation->role->value);
});

test('user can only be invited by team member', function (string $role) {
    $user = User::factory()->create();

    $team = Team::factory()->create();

    $action = app(CreateTeamInvitationAction::class);

    $action->handle($user, $team, [
        'email' => 'test@example.com',
        'role' => $role,
    ]);
})->with(['admin', 'collaborator'])->throws(AuthorizationException::class);

test('user can not be invited with invalid field', function (string $field) {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $payload = [
        'email' => 'test@example.com',
        'role' => 'collaborator',
    ];

    $payload[$field] = '';

    $action = app(CreateTeamInvitationAction::class);

    $action->handle($user, $team, $payload);
})->with(['email', 'role'])->throws(ValidationException::class);

test('member can not be invited', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $team->users()->attach($member = User::factory()->create(), [
        'role' => 'collaborator',
    ]);

    $action = app(CreateTeamInvitationAction::class);

    $action->handle($user, $team, [
        'email' => $member->email,
        'role' => 'collaborator',
    ]);
})->throws(ValidationException::class);

<?php

declare(strict_types=1);

use App\Actions\Team\AcceptTeamInvitationAction;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('user can accept invitation', function () {
    $user = User::factory()->create();

    $invitation = TeamInvitation::factory()->create([
        'email' => $user->email,
    ]);

    $action = app(AcceptTeamInvitationAction::class);

    $action->handle($invitation);

    $this->assertNull($invitation->fresh());
});

test('guest can not accept invitation', function () {
    $invitation = TeamInvitation::factory()->create();

    $action = app(AcceptTeamInvitationAction::class);

    $action->handle($invitation);
})->throws(ValidationException::class);

test('member can not accept invitation', function () {
    $user = User::factory()->create();

    $team = Team::factory()->create();
    $team->users()->attach($user, ['role' => 'collaborator']);

    $invitation = TeamInvitation::factory()->for($team)->create([
        'email' => $user->email,
    ]);

    $action = app(AcceptTeamInvitationAction::class);

    $action->handle($invitation);
})->throws(ValidationException::class);

<?php

declare(strict_types=1);

use App\Actions\Team\DeleteTeamInvitationAction;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('invitation can be deleted', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var TeamInvitation $invitation */
    $invitation = TeamInvitation::factory()->for($team)->create();

    $action = app(DeleteTeamInvitationAction::class);

    $action->handle($user, $invitation);

    $this->assertNull($invitation->fresh());
});

test('only members can delete invitation', function () {
    $user = User::factory()->withTeam()->create();

    $team = Team::factory()->create();

    /** @var TeamInvitation $invitation */
    $invitation = TeamInvitation::factory()->for($team)->create();

    $action = app(DeleteTeamInvitationAction::class);

    $action->handle($user, $invitation);
})->throws(AuthorizationException::class);

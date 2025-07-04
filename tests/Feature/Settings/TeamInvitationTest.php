<?php

declare(strict_types=1);

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\TeamInvitation;
use App\Models\User;

test('invitation can be created', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->post('/settings/teams/'.$team->id.'/invitations', [
            'email' => 'test@example.com',
            'role' => 'collaborator',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    expect($team->invitations->count())->toBe(1);

    $invitation = $team->invitations->first();

    expect($invitation->email)->toBe('test@example.com')
        ->and($invitation->role)->toBe(TeamRoles::COLLABORATOR);
});

test('invitation can not be created for member', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $response = $this
        ->actingAs($user)
        ->from('/settings/teams/'.$team->id.'/edit')
        ->post('/settings/teams/'.$team->id.'/invitations', [
            'email' => $member->email,
            'role' => 'collaborator',
        ]);

    $response
        ->assertSessionHasErrors(['email'])
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');
});

test('invitation can be deleted', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var TeamInvitation $invitation */
    $invitation = TeamInvitation::factory()->for($team)->create();

    $response = $this
        ->actingAs($user)
        ->delete('/settings/team-invitations/'.$invitation->id);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    expect($invitation->fresh())->toBeNull();
});

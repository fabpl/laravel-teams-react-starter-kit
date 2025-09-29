<?php

declare(strict_types=1);

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;

test('team members page is displayed', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->get('/settings/teams/'.$team->id.'/members');

    $response->assertOk();
});

test('member can be updated', function () {
    /** @var User $user */
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var User $member */
    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $response = $this
        ->actingAs($user)
        ->patch('/settings/teams/'.$team->id.'/members/'.$member->id, [
            'role' => 'admin',
        ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    /** @var User $member */
    $member = $team->users()->where('email', $member->email)->first();

    expect($member->membership->role)->toBe(TeamRoles::ADMIN);
});

test('member can be deleted', function () {
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var User $member */
    $member = User::factory()->create();
    $team->users()->attach($member, [
        'role' => 'collaborator',
    ]);

    $response = $this
        ->actingAs($user)
        ->delete('/settings/teams/'.$team->id.'/members/'.$member->id);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    /** @var User $member */
    $member = $team->users()->where('email', $member->email)->first();

    expect($member)->toBeNull();
});

test('users can leave teams', function () {
    /** @var User $user */
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    /** @var User $user */
    $member = User::factory()->create();

    $team->users()->attach($member, [
        'role' => 'admin',
    ]);

    $response = $this
        ->actingAs($member)
        ->delete('/settings/teams/'.$team->id.'/members/'.$member->id);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/settings/teams/'.$team->id.'/edit');

    $member = $team->users()->where('email', $member->email)->first();

    expect($member)->toBeNull();
});

test('team owners cant leave their own team', function () {
    /** @var User $user */
    $user = User::factory()->withTeam()->create();

    /** @var Team $team */
    $team = $user->currentTeam;

    $response = $this
        ->actingAs($user)
        ->delete('/settings/teams/'.$team->id.'/members/'.$user->id);

    $response->assertSessionHasErrors(['team']);

    $member = $team->users()->where('email', $user->email)->first();

    expect($member)->not->toBeNull();
});

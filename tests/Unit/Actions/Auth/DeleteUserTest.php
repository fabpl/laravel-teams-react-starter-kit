<?php

declare(strict_types=1);

use App\Actions\Auth\DeleteUserAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('user can delete their account', function () {
    $user = User::factory()->create();

    $action = app(DeleteUserAction::class);

    $action->handle($user);

    $this->assertNull($user->fresh());

    $this->assertDatabaseMissing('users', ['id' => $user->id]);
});

test('team are deleted if one admin', function () {
    $user = User::factory()->withTeam()->create();

    /** @var App\Models\Team $team */
    $team = $user->currentTeam;

    $action = app(DeleteUserAction::class);

    $action->handle($user);

    $this->assertNull($team->fresh());
});

test('team are not deleted if another admin', function () {
    $user = User::factory()->withTeam()->create();

    /** @var App\Models\Team $team */
    $team = $user->currentTeam;

    $member = User::factory()->create();
    $team->users()->attach($member, ['role' => 'admin']);

    $action = app(DeleteUserAction::class);

    $action->handle($user);

    $this->assertNotNull($team->fresh());
});

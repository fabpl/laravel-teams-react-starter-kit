<?php

declare(strict_types=1);

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))->toBe([
        'id',
        'current_team_id',
        'name',
        'email',
        'email_verified_at',
        'created_at',
        'updated_at',
    ]);
});

test('casts', function () {
    $user = User::factory()->withTeam()->create()->fresh();

    expect($user->currentTeam)->toBeInstanceOf(Team::class);
});

test('team role', function () {
    $user = User::factory()->create();

    $team = Team::factory()->create();

    $team->users()->attach($user->id, [
        'role' => 'admin',
    ]);

    expect($user->fresh()->teamRole($team))->toBe(TeamRoles::ADMIN);
});

test('without team role', function () {
    $user = User::factory()->create();

    $team = Team::factory()->create();

    expect($user->fresh()->teamRole($team))->toBeNull();
});

test('team permissions', function () {
    $user = User::factory()->create();

    $team = Team::factory()->create();

    $team->users()->attach($user->id, [
        'role' => 'admin',
    ]);

    expect($user->fresh()->teamPermissions($team))->not->toBeEmpty();
});

test('without team permissions', function () {
    $user = User::factory()->create();

    $team = Team::factory()->create();

    expect($user->fresh()->teamPermissions($team))->toBeEmpty();
});

<?php

declare(strict_types=1);

use App\Enums\TeamRoles;
use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('to array', function () {
    $invitation = TeamInvitation::factory()->create()->fresh();

    expect(array_keys($invitation->toArray()))->toBe([
        'id',
        'team_id',
        'email',
        'role',
        'created_at',
        'updated_at',
    ]);
});

test('casts', function () {
    $invitation = TeamInvitation::factory()->create()->fresh();

    expect($invitation->team)->toBeInstanceOf(Team::class)
        ->and($invitation->role)->toBeInstanceOf(TeamRoles::class);
});

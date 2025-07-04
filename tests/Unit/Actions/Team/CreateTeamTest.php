<?php

declare(strict_types=1);

use App\Actions\Team\CreateTeamAction;
use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;

uses(RefreshDatabase::class);

test('new teams can be create', function () {
    $user = User::factory()->create();

    $action = app(CreateTeamAction::class);

    $team = $action->handle($user, [
        'name' => 'Test Team',
    ]);

    $this->assertInstanceOf(Team::class, $team);
    $this->assertEquals('Test Team', $team->name);
    $this->assertTrue($user->currentTeam->is($team));
});

test('teams can not be created with invalid field', function (string $field) {
    $user = User::factory()->create();

    $payload = [
        'name' => 'Test Team',
    ];

    $payload[$field] = '';

    $action = app(CreateTeamAction::class);

    $action->handle($user, $payload);
})->with(['name'])->throws(ValidationException::class);

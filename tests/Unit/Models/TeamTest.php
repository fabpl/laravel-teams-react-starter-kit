<?php

declare(strict_types=1);

use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('to array', function () {
    $team = Team::factory()->create()->fresh();

    expect(array_keys($team->toArray()))->toBe([
        'id',
        'name',
        'created_at',
        'updated_at',
    ]);
});

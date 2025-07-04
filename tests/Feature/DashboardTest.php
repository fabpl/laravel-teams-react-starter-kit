<?php

declare(strict_types=1);

use App\Models\User;

test('authenticated users can visit the dashboard', function () {
    $this->actingAs($user = User::factory()->withTeam()->create());

    $this->get('/dashboard')->assertOk();
});

test('guests are redirected to the login page', function () {
    $this->get('/dashboard')->assertRedirect('/login');
});

test('non team tenant are redirected to the login page', function () {
    $this->actingAs($user = User::factory()->create());

    $this->get('/dashboard')->assertRedirect('/settings/teams/create');
});

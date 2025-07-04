<?php

declare(strict_types=1);

use App\Models\TeamInvitation;
use App\Models\User;

test('invitation can be accepted', function () {
    $user = User::factory()->create();

    $invitation = TeamInvitation::factory()->create([
        'email' => $user->email,
    ]);

    $response = $this
        ->actingAs($user)
        ->get($invitation->accept_url);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/dashboard');

    $this->assertNull($invitation->fresh());
});

test('guests are redirected to the login page', function () {
    $invitation = TeamInvitation::factory()->create();

    $this->get($invitation->accept_url)->assertRedirect('/login');
});

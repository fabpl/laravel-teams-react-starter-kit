<?php

declare(strict_types=1);

use App\Actions\Auth\SendUserVerifyLinkAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('email verification notification can be sent', function () {
    $user = User::factory()->unverified()->create();

    $action = app(SendUserVerifyLinkAction::class);

    $status = $action->handle($user);

    $this->assertTrue($status);
});

test('verified users can not be notified', function () {
    $user = User::factory()->create();

    $action = app(SendUserVerifyLinkAction::class);

    $status = $action->handle($user);

    $this->assertFalse($status);
});

<?php

declare(strict_types=1);

use App\Actions\Auth\SendUserResetLinkAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Password;

uses(RefreshDatabase::class);

test('reset password link can be requested', function () {
    $user = User::factory()->create();

    $action = app(SendUserResetLinkAction::class);

    $status = $action->handle(['email' => $user->email]);

    $this->assertTrue($status === Password::RESET_LINK_SENT);
});

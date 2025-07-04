<?php

declare(strict_types=1);

use App\Actions\Auth\UpdateUserPasswordAction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

test('password can be updated', function () {
    $user = User::factory()->create();

    $action = app(UpdateUserPasswordAction::class);

    $action->handle($user, [
        'password' => 'new-password',
    ]);

    $user = $user->refresh();

    $this->assertTrue(Hash::check('new-password', $user->password));
});

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Auth\UpdateUserPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

final class PasswordController extends Controller
{
    /**
     * Show the user's password settings page.
     */
    public function edit(): Response
    {
        return Inertia::render('settings/password');
    }

    /**
     * Update the user's password.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(PasswordUpdateRequest $request, UpdateUserPasswordAction $updateUserPassword): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var array<string, mixed> $input */
        $input = $request->all();

        $updateUserPassword->handle($user, $input);

        return back();
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Actions\Auth\DeleteUserAction;
use App\Actions\Auth\UpdateUserProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ProfileDestroyRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

final class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    public function edit(Request $request): Response
    {
        /** @var User $user */
        $user = $request->user();

        return Inertia::render('settings/profile', [
            'mustVerifyEmail' => ! $user->hasVerifiedEmail(),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile settings.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, UpdateUserProfileAction $updateUserProfile): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var array<string, mixed> $input */
        $input = $request->all();

        $updateUserProfile->handle($user, $input);

        return to_route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(ProfileDestroyRequest $request, DeleteUserAction $deleteUser): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        Auth::logout();

        $deleteUser->handle($user);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

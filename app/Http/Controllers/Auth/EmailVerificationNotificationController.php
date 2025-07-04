<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendUserVerifyLinkAction;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

final class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request, SendUserVerifyLinkAction $sendUserVerifyLink): RedirectResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($sendUserVerifyLink->handle($user)) {
            return back()->with('status', 'verification-link-sent');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }
}

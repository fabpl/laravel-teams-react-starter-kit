<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Actions\Auth\SendUserResetLinkAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class PasswordResetLinkController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('auth/forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, SendUserResetLinkAction $sendUserResetLink): RedirectResponse
    {
        /** @var array<string, mixed> $input */
        $input = $request->all();

        $sendUserResetLink->handle($input);

        return back()->flash(__('A reset link will be sent if the account exists.'), variant: 'success');
    }
}

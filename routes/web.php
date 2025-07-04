<?php

declare(strict_types=1);

use App\Http\Controllers\AcceptTeamInvitationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Settings\CurrentTeamController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\TeamController;
use App\Http\Controllers\Settings\TeamInvitationController;
use App\Http\Controllers\Settings\TeamMemberController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->middleware('throttle:6,1')->name('verification.send');
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');

    Route::middleware('verified')->group(function () {
        Route::get('settings/teams/create', [TeamController::class, 'create'])->name('teams.create');
        Route::post('settings/teams', [TeamController::class, 'store'])->name('teams.store');

        Route::get('/team-invitations/{invitation}', AcceptTeamInvitationController::class)->middleware(['signed'])->name('team-invitations.accept');

        Route::middleware('current-team')->group(function () {
            Route::get('dashboard', fn () => Inertia::render('dashboard'))->name('dashboard');

            Route::redirect('settings', 'settings/profile');

            Route::get('settings/profile', [ProfileController::class, 'edit'])->name('profile.edit');
            Route::patch('settings/profile', [ProfileController::class, 'update'])->name('profile.update');
            Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

            Route::put('settings/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');

            Route::get('settings/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
            Route::delete('settings/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
            Route::patch('settings/teams/{team}', [TeamController::class, 'update'])->name('teams.update');

            Route::post('settings/teams/{team}/invitations', [TeamInvitationController::class, 'store'])->name('team-invitations.store');
            Route::delete('settings/team-invitations/{invitation}', [TeamInvitationController::class, 'destroy'])->name('team-invitations.destroy');

            Route::patch('settings/teams/{team}/members/{member}', [TeamMemberController::class, 'update'])->name('team-members.update');
            Route::delete('settings/teams/{team}/members/{member}', [TeamMemberController::class, 'destroy'])->name('team-members.destroy');

            Route::get('settings/password', [PasswordController::class, 'edit'])->name('password.edit');
            Route::put('settings/password', [PasswordController::class, 'update'])->name('password.update');

            Route::get('settings/appearance', fn () => Inertia::render('settings/appearance'))->name('appearance');
        });
    });
});

Route::get('/', fn () => Inertia::render('welcome'))->name('home');

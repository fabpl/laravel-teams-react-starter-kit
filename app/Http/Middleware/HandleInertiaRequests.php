<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

final class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        /** @var string $quote */
        $quote = Inspiring::quotes()->random();

        /** @var string $message */
        /** @var string $author */
        [$message, $author] = str($quote)->explode('-');

        /** @var array<string, mixed> $parent */
        $parent = parent::share($request);

        return array_merge($parent, [
            'name' => config('app.name'),
            'quote' => ['message' => mb_trim($message), 'author' => mb_trim($author)],
            'auth' => function () use ($request): array {
                if (! $request->user()) {
                    return [
                        'user' => null,
                        'currentTeam' => null,
                        'teams' => null,
                    ];
                }

                /** @var \App\Models\User $user */
                $user = $request->user();

                return [
                    'user' => $user->toArray(),
                    'currentTeam' => $user->teams->firstWhere('id', $user->current_team_id),
                    'permissions' => $user->currentTeam ? $user->teamPermissions($user->currentTeam) : [],
                    'teams' => $user->teams,
                ];
            },
            'ziggy' => fn (): array => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        ]);
    }
}

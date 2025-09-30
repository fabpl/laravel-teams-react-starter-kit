<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

final class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->configureCommands();
        $this->configureDates();
        $this->configureFlashResponse();
        $this->configureModels();
        $this->configurePasswords();
        $this->configureUrls();
        $this->configureVite();
    }

    private function configureCommands(): void
    {
        DB::prohibitDestructiveCommands(app()->isProduction());
    }

    private function configureDates(): void
    {
        Date::use(CarbonImmutable::class);
    }

    private function configureFlashResponse(): void
    {
        RedirectResponse::macro('flash', function (string $title, ?string $description = null, string $variant = 'default'): RedirectResponse {
            /** @var RedirectResponse $this */
            return $this->with('flash', [
                'title' => $title,
                'description' => $description,
                'variant' => $variant,
            ]);
        });
    }

    private function configureModels(): void
    {
        Model::shouldBeStrict();
        Model::unguard();
    }

    private function configurePasswords(): void
    {
        Password::defaults(fn (): ?Password => app()->isProduction() ? Password::min(8)->letters()->mixedCase()->numbers()->symbols() : null);
    }

    private function configureUrls(): void
    {
        URL::forceHttps(app()->isProduction());
    }

    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }
}

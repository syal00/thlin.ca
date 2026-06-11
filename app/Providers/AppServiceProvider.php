<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->environment('production') && env('VERCEL')) {
            URL::forceScheme('https');
        }

        if (! $this->app->runningInConsole()) {
            $forwardedHost = request()->headers->get('X-Forwarded-Host');
            if ($forwardedHost) {
                $scheme = request()->headers->get('X-Forwarded-Proto', 'https');
                URL::forceRootUrl($scheme.'://'.trim(explode(',', $forwardedHost)[0]));
            }
        }

        View::share('thlin', config('thlin'));
        View::share('navigation', config('thlin.navigation'));
    }
}

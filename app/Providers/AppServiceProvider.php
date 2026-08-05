<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
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
        if ($this->app->environment('production')) {
            // CLOUDINARY_URL is intentionally optional — CloudinaryStorage
            // falls back when unset. Requiring it here breaks Vercel builds
            // (composer runs `artisan package:discover` with APP_ENV=production).
            $required = [
                'APP_KEY' => env('APP_KEY'),
                'DB_URL' => env('DB_URL') ?: env('DATABASE_URL'),
            ];

            if (! config('services.tinymce.self_hosted', true)) {
                $required['TINYMCE_API_KEY'] = env('TINYMCE_API_KEY');
            }

            foreach ($required as $key => $value) {
                if (empty($value)) {
                    throw new \RuntimeException("Required environment variable [{$key}] is not set.");
                }
            }
        }

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

        ResetPassword::createUrlUsing(function (object $notifiable, string $token): string {
            return route('admin.password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ]);
        });

        View::share('thlin', config('thlin'));
        View::share('navigation', config('thlin.navigation'));
    }
}

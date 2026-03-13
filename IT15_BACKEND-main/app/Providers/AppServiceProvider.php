<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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
        RateLimiter::for('weather', function (Request $request) {
            $key = $request->ip();

            return Limit::perMinute(30)->by($key)->response(function () {
                return response()->json([
                    'message' => 'Too many weather requests. Please try again in a minute.',
                ], 429);
            });
        });
    }
}

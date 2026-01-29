<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Http\Request; // ✅ CORRECT
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        RateLimiter::for('shorten', function (Request $request) {

            // Token-authenticated clients → higher limit
            if ($request->user()) {
                return Limit::perMinute(60)
                    ->by($request->user()->id);
            }

            // Anonymous clients → strict limit
            return Limit::perMinute(10)
                ->by($request->ip());
        });
    }
}

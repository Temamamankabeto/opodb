<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        RateLimiter::for('login', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });
        // ✅ Register your custom middleware
        Route::aliasMiddleware('auth.check', \App\Http\Middleware\AuthCheck::class);

        $this->routes(function () {
            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });

        Paginator::useBootstrapFive();
    }
}

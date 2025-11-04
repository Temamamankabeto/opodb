<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Pagination\Paginator;


class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
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

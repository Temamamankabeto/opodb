<?php

namespace App\Providers;

use App\Http\Controllers\LayoutController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        // Automatically set app locale from session
        View::composer('components.*', function ($view) {
            $layoutData = LayoutController::getLayoutData();
            $view->with($layoutData);
        });
    }
}

<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register route middleware aliases
        // App\Http\Middleware\SecurityHeaders::class;
        $middleware->web(append: [
            \App\Http\Middleware\SetLocale::class,
        ]);

        $middleware
            ->alias([
                'auth.check' => \App\Http\Middleware\AuthCheck::class,
                'role' => \App\Http\Middleware\RoleMiddleware::class,
                'setlocale' => \App\Http\Middleware\SetLocale::class,
                // 'security.headers'  => \App\Http\Middleware\SecurityHeaders::class,

            ]);

        // (Optional) Register global middleware
        // $middleware->append(\App\Http\Middleware\SomeGlobalMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

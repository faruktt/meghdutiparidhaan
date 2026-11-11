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
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'checkout/save-draft',
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\TrackVisitor::class,
        ]);

        $middleware->alias([
            'admin'     => \App\Http\Middleware\AdminAuth::class,
            'admin.can' => \App\Http\Middleware\CheckAdminPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets;
use Illuminate\Http\Middleware\HandleCors;
use App\Http\Middleware\EnsureRole;  // <– your custom middleware

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 1) keep your existing global/API middleware
        $middleware->api(append: [
            HandleCors::class,
            AddLinkHeadersForPreloadedAssets::class,
        ]);

        // 2) register your 'role' alias for route‐middleware
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);
    })
    ->withExceptions(function () {
    })->create();

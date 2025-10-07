<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(dirname(__DIR__))
    ->withRouting(
        null,
        __DIR__.'/../routes/web.php',
        null,
        __DIR__.'/../routes/console.php',
        null,
        null,
        '/up'
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'check.approval' => \App\Http\Middleware\CheckApprovalPermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

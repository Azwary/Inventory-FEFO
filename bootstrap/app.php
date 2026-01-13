<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Session\TokenMismatchException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )

    // ✅ MIDDLEWARE
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })

    // ✅ EXCEPTION HANDLER (TEMPAT YANG BENAR)
    ->withExceptions(function (Exceptions $exceptions) {

        // 🔴 HANDLE 419 / CSRF / SESSION EXPIRED
        $exceptions->render(function (TokenMismatchException $e, $request) {
            return redirect()
                ->route('login')
                ->with('message', 'Session habis, silakan login kembali');
        });
    })

    ->create();

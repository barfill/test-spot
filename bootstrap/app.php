<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(
            append: [
            HandleInertiaRequests::class,
            \App\Http\Middleware\Language::class,
        ]);
        // Wyłączenie CSRF dla API compile
        $middleware->validateCsrfTokens(except: [
            '*/dashboard/*/assignments/*/compile',
        ]);
        $middleware->redirectGuestsTo(function($request) {
            $locale = $request->route('locale')
                ?? $request->session()->get('locale')
                ?? config('app.locale');
            return route('login', ['locale' => $locale]);
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;

class Language
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $supportedLocales = config('app.supported_locales');
        $routeLocale = $request->route('locale');
        $sessionLocale = $request->hasSession() ? $request->session()->get('locale') : null;

        $locale = $routeLocale ?? $sessionLocale ?? config('app.locale');

        if (!in_array($locale, $supportedLocales)) {
            $locale = config('app.fallback_locale');
        }

        if ($routeLocale && ($routeLocale !== $sessionLocale)) {
            $request->session()->put('locale', $locale);
        }

        App::setLocale($locale);
        Carbon::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        logger()->info('Language middleware', [
        'locale' => $locale,
        'defaults' => \Illuminate\Support\Facades\URL::getDefaultParameters()
        ]);

        return $next($request);
    }
}

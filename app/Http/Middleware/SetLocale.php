<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * SetLocale Middleware
 *
 * Reads the 'locale' from the session and sets the application locale.
 * This ensures multilingual support persists across requests.
 *
 * Registered as a global middleware in bootstrap/app.php
 *
 * Demonstrates: Middleware, Localization, Sessions
 */
class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        // Check session for locale preference
        $locale = session('locale');

        // If no session locale, try from logged-in user preference
        if (!$locale && auth()->check()) {
            $locale = auth()->user()->locale;
        }

        // Default to app locale if nothing set
        $locale = $locale ?? config('app.locale', 'en');

        // Only set supported locales
        if (in_array($locale, ['en', 'hi'])) {
            app()->setLocale($locale);
        }

        return $next($request);
    }
}

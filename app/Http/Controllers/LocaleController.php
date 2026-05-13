<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * LocaleController
 *
 * Handles language switching between English and Hindi.
 * Demonstrates: Sessions, App::setLocale(), named routes
 */
class LocaleController extends Controller
{
    /**
     * Switch the application locale.
     * GET /locale/{lang}
     *
     * @param string $lang — 'en' or 'hi'
     */
    public function switch(string $lang)
    {
        // Only allow supported locales
        $supported = ['en', 'hi'];

        if (!in_array($lang, $supported)) {
            return back()->with('error', 'Language not supported.');
        }

        // Store locale in session
        session(['locale' => $lang]);
        app()->setLocale($lang);

        // If logged in, persist to database
        if (auth()->check()) {
            auth()->user()->update(['locale' => $lang]);
        }

        return back()->with('success', $lang === 'hi' ? 'भाषा बदल दी गई!' : 'Language switched to English!');
    }
}

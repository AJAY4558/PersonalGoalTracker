<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

/**
 * AuthController
 *
 * Handles all authentication logic:
 *   - Registration with email notification
 *   - Login with Remember Me
 *   - Logout
 *   - Session management
 *
 * Demonstrates: Request handling, Responses, Cookies, Redirects, Named Routes
 */
class AuthController extends Controller
{
    // ─────────────────────────────────────────────
    // REGISTRATION
    // ─────────────────────────────────────────────

    /** Show the registration form */
    public function showRegister()
    {
        // If already logged in, redirect to dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    /** Handle registration form submission */
    public function register(RegisterRequest $request)
    {
        // Create the user (password is auto-hashed via model cast)
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
            'locale'   => 'en',
        ]);

        // Send welcome email (non-blocking — logged to storage/logs/laravel.log)
        try {
            Mail::to($user->email)->send(new WelcomeMail($user));
        } catch (\Exception $e) {
            // Email failure should not block registration
            logger()->warning('Welcome email failed: ' . $e->getMessage());
        }

        // Log activity to MongoDB
        ActivityLogService::log('user.registered', "New user registered: {$user->name}", [
            'user_id' => $user->id,
            'email'   => $user->email,
        ]);

        // Auto-login after registration
        Auth::login($user);

        // Store success message in session (flash)
        session()->flash('success', "Welcome, {$user->name}! Your account has been created.");

        return redirect()->route('dashboard');
    }

    // ─────────────────────────────────────────────
    // LOGIN
    // ─────────────────────────────────────────────

    /** Show the login form */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /** Handle login form submission */
    public function login(Request $request)
    {
        // Validate login credentials
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required'    => 'Please enter your email address.',
            'password.required' => 'Please enter your password.',
        ]);

        $remember = $request->boolean('remember'); // Remember Me checkbox

        // Attempt authentication
        if (Auth::attempt($credentials, $remember)) {
            // Regenerate session to prevent session fixation attacks
            $request->session()->regenerate();

            // Set locale from user preference
            app()->setLocale(Auth::user()->locale ?? 'en');
            session(['locale' => Auth::user()->locale ?? 'en']);

            // Log login activity
            ActivityLogService::log('user.login', 'User logged in', [
                'user_id' => Auth::id(),
                'ip'      => $request->ip(),
            ]);

            // Store a cookie to track last login (demonstration)
            $cookie = Cookie::make('last_login', now()->toDateTimeString(), 43200); // 30 days

            session()->flash('success', 'Welcome back, ' . Auth::user()->name . '!');

            return redirect()
                ->intended(route('dashboard'))
                ->withCookie($cookie);
        }

        // Authentication failed — throw validation exception (repopulates email field)
        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    // ─────────────────────────────────────────────
    // LOGOUT
    // ─────────────────────────────────────────────

    /** Handle user logout */
    public function logout(Request $request)
    {
        ActivityLogService::log('user.logout', 'User logged out', [
            'user_id' => Auth::id(),
        ]);

        Auth::logout();

        // Invalidate the session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been logged out successfully.');
    }
}

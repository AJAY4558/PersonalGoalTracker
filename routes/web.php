<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\LocaleController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Personal Goal Tracker
|--------------------------------------------------------------------------
|
| Demonstrates:
|   - Basic Routing
|   - Named Routes
|   - Route Groups with middleware
|   - Route Prefixing
|   - Parameter Constraints
|   - Route Model Binding (in GoalController)
|   - Redirects
|
*/

// ─────────────────────────────────────────────────────────────────────────
// PUBLIC ROUTES — Accessible without login
// ─────────────────────────────────────────────────────────────────────────

// Home / Landing Page
Route::get('/', function () {
    return view('home');
})->name('home');

// Locale / Language Switcher
// Parameter constraint: only 'en' or 'hi' allowed
Route::get('/locale/{lang}', [LocaleController::class, 'switch'])
    ->where('lang', 'en|hi')
    ->name('locale.switch');


// ─────────────────────────────────────────────────────────────────────────
// GUEST ROUTES — Only for non-authenticated users
// ─────────────────────────────────────────────────────────────────────────

Route::middleware('guest')->group(function () {

    // Registration
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

});


// ─────────────────────────────────────────────────────────────────────────
// AUTHENTICATED ROUTES — Require login
// Middleware: 'auth' + 'locale' (SetLocale)
// ─────────────────────────────────────────────────────────────────────────

Route::middleware(['auth'])->group(function () {

    // Logout (POST for CSRF protection)
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── GOALS — Resource Routes ──────────────────────────────────────────
    // Generates: index, create, store, show, edit, update, destroy
    // URLs: /goals, /goals/create, /goals/{goal}, /goals/{goal}/edit
    Route::resource('goals', GoalController::class);

    // ─── PROFILE ──────────────────────────────────────────────────────────
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('profile');          // /profile
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');                   // PUT /profile
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('profile.password'); // /profile/password
        Route::put('/locale', [ProfileController::class, 'updateLocale'])->name('profile.locale');
    });

});


// ─────────────────────────────────────────────────────────────────────────
// ADMIN ROUTES — Prefix group with parameter constraints
// ─────────────────────────────────────────────────────────────────────────

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'adminIndex'])->name('dashboard');
});

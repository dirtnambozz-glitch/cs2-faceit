<?php
use App\Http\Controllers\Auth\SteamAuthController;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Leaderboard;
use App\Livewire\MatchLobby;
use App\Livewire\Matchmaking;
use App\Livewire\Profile;
use App\Livewire\ServerSettings;
use App\Http\Controllers\Api\MatchZyWebhookController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/login', Login::class)->name('login')->middleware('guest');
Route::get('/register', Register::class)->name('register')->middleware('guest');
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Steam auth
Route::get('/auth/steam', [SteamAuthController::class, 'redirect'])->name('auth.steam');
Route::get('/auth/steam/callback', [SteamAuthController::class, 'callback'])->name('auth.steam.callback');
Route::post('/auth/steam/direct', [SteamAuthController::class, 'directLogin'])
    ->name('auth.steam.direct')
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

// Public pages
Route::get('/leaderboard', Leaderboard::class)->name('leaderboard');
Route::get('/player/{id}', Profile::class)->name('profile.player');
Route::get('/profile/{id?}', Profile::class)->name('profile');

// Protected routes (require auth)
Route::middleware('auth')->group(function () {
    Route::get('/', Matchmaking::class)->name('home');
    Route::get('/match/{uuid}', MatchLobby::class)->name('match.lobby');
    Route::get('/server', ServerSettings::class)->name('server.settings');
    Route::get('/admin', ServerSettings::class)->name('admin.dashboard');
});

// API webhook (no CSRF)
Route::post('/api/matchzy/events', [MatchZyWebhookController::class, 'handle'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class]);

<?php

namespace App\Providers;

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Models\GameSession;
use App\Models\Quiz;
use App\Policies\HostSessionPolicy;
use App\Policies\QuizPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(Quiz::class, QuizPolicy::class);
        Gate::policy(GameSession::class, HostSessionPolicy::class);

        Route::middleware('web')->group(function (): void {
            Route::middleware('guest')->group(function (): void {
                Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
                Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
                Route::get('/register', [AuthenticatedSessionController::class, 'createSignup'])->name('register');
                Route::post('/register', [AuthenticatedSessionController::class, 'submitSignup'])->name('register.store');
            });

            Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])
                ->middleware('auth')
                ->name('logout');
        });
    }
}

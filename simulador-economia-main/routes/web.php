<?php

use App\Http\Controllers\Auth\GuestAccessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ComparisonController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScenarioController;
use App\Http\Controllers\SimulationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::view('/', 'landing')->name('home');

Route::post('/guest-access', [GuestAccessController::class, 'store'])
    ->middleware('guest')
    ->name('guest.access');

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::resource('scenarios', ScenarioController::class);
    Route::post('/scenarios/{scenario}/run',
        [SimulationController::class, 'run'])
        ->name('simulations.run');
    Route::get('/runs/{run}/results',
        [SimulationController::class, 'results'])
        ->name('simulations.results');
    Route::post('/scenarios/{scenario}/reset',
        [SimulationController::class, 'reset'])
        ->name('simulations.reset');
    Route::get('/compare', [ComparisonController::class, 'index'])
        ->name('compare.index');
    Route::post('/compare', [ComparisonController::class, 'compare'])
        ->name('compare.run');
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
    Route::get('/reports/{run}/pdf', [ReportController::class, 'pdf'])
        ->name('reports.pdf');
    Route::get('/reports/{run}/csv', [ReportController::class, 'csv'])
        ->name('reports.csv');
    Route::get('/reports/{run}/json', [ReportController::class, 'json'])
        ->name('reports.json');
    Route::get('/help', [HelpController::class, 'index'])
        ->name('help.index');
});

require __DIR__.'/auth.php';

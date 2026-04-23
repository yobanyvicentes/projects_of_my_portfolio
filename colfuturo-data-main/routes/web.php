<?php

use App\Http\Controllers\AcademicInsightsController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AcademicInsightsController::class, 'index'])
    ->name('home');

require __DIR__.'/academic-insights.php';

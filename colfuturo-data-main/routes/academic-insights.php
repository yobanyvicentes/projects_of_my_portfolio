<?php

use App\Http\Controllers\AcademicInsightsController;
use Illuminate\Support\Facades\Route;

Route::get('/academic-insights', [AcademicInsightsController::class, 'index'])
    ->name('academic-insights.index');

Route::post('/academic-insights/recommend', [AcademicInsightsController::class, 'recommend'])
    ->name('academic-insights.recommend');

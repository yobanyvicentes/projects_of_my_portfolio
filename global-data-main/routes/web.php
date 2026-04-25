<?php

use App\Http\Controllers\EconomicDashboardPageController;
use Illuminate\Support\Facades\Route;

Route::controller(EconomicDashboardPageController::class)->group(function () {
    Route::get('/', 'home')->name('home');
    Route::get('/world-bank', 'worldBank')->name('sources.world-bank');
    Route::get('/imf', 'imf')->name('sources.imf');
    Route::get('/oecd', 'oecd')->name('sources.oecd');
    Route::get('/un-data', 'unData')->name('sources.un-data');
    Route::get('/dbnomics', 'dbnomics')->name('sources.dbnomics');
    Route::get('/fred', 'fred')->name('sources.fred');
    Route::get('/compare', 'compare')->name('compare');
});

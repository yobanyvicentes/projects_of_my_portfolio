<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\FlatController;
use App\Http\Controllers\CurrentFlatController;
use App\Http\Controllers\JoinRequestController;
use App\Http\Controllers\JoinRequestReviewController;
use App\Http\Controllers\FlatRoleController;
use App\Http\Controllers\MemberProfileController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\ChoreController;
use App\Http\Controllers\ShoppingItemController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn()=>view('welcome'))->name('home');
Route::get('/dashboard', DashboardController::class)->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function(){
    Route::get('/receipts', [ReceiptController::class, 'index'])->name('receipts.index');
    Route::post('/receipts', [ReceiptController::class, 'store'])->name('receipts.store');
    Route::post('/receipts/{receipt}/delete', [ReceiptController::class, 'destroy'])->name('receipts.delete');
    Route::post('/current-flat',[CurrentFlatController::class,'switch'])->name('current-flat.switch');
    Route::get('/flats/create',[FlatController::class,'create'])->name('flats.create');
    Route::post('/flats',[FlatController::class,'store'])->name('flats.store');
    Route::get('/join',[JoinRequestController::class,'create'])->name('join.create');
    Route::post('/join',[JoinRequestController::class,'store'])->name('join.store');
    Route::get('/join/requests',[JoinRequestReviewController::class,'index'])->name('join.requests');
    Route::post('/join/{joinRequest}/accept',[JoinRequestReviewController::class,'accept'])->name('join.accept');
    Route::post('/join/{joinRequest}/decline',[JoinRequestReviewController::class,'decline'])->name('join.decline');
    Route::get('/roles',[FlatRoleController::class,'index'])->name('roles.index');
    Route::get('/my-profile',[MemberProfileController::class,'edit'])->name('member-profile.edit');
    Route::post('/my-profile',[MemberProfileController::class,'update'])->name('member-profile.update');
    Route::get('/activity',[ActivityController::class,'index'])->name('activity.index');
    Route::get('/chores',[ChoreController::class,'index'])->name('chores.index');
    Route::post('/chores',[ChoreController::class,'store'])->name('chores.store');
    Route::post('/chores/{chore}/complete',[ChoreController::class,'complete'])->name('chores.complete');
    Route::get('/shopping',[ShoppingItemController::class,'index'])->name('shopping.index');
    Route::post('/shopping',[ShoppingItemController::class,'store'])->name('shopping.store');
    Route::post('/shopping/{item}/complete',[ShoppingItemController::class,'complete'])->name('shopping.complete');
});

require __DIR__.'/auth.php';
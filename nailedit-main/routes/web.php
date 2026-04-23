<?php

use App\Http\Controllers\{GameSessionController,JoinGameController,PlayerAnswerController,QuestionController,QuizController,SessionControlController,SessionPlayController};
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('welcome');

Route::controller(QuizController::class)->group(function () {
    Route::get('/quizzes', 'index')->name('quizzes.index');
    Route::get('/quizzes/create', 'create')->name('quizzes.create');
    Route::post('/quizzes', 'store')->name('quizzes.store');
    Route::get('/quizzes/{quiz}', 'show')->name('quizzes.show');
});

Route::post('/quizzes/{quiz}/questions', [QuestionController::class, 'store'])->name('quizzes.questions.store');
Route::post('/quizzes/{quiz}/sessions', [GameSessionController::class, 'store'])->name('quizzes.sessions.store');
Route::get('/sessions/{session}', [GameSessionController::class, 'show'])->name('sessions.show');
Route::post('/sessions/{session}/start', [SessionControlController::class, 'start'])->name('sessions.start');
Route::post('/sessions/{session}/next', [SessionControlController::class, 'next'])->name('sessions.next');
Route::get('/join', [JoinGameController::class, 'create'])->name('sessions.join');
Route::post('/join', [JoinGameController::class, 'store'])->name('sessions.join.store');
Route::get('/play/{session}', [SessionPlayController::class, 'show'])->name('sessions.play');
Route::post('/play/{session}/answer', [PlayerAnswerController::class, 'store'])->name('sessions.answer.store');

<?php

use App\Http\Controllers\App\CatalogController;
use App\Http\Controllers\App\FocusSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\CardController;
use App\Http\Controllers\PomodoroController;
use App\Models\Card;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('pomodoro');
})->name('index');


Route::get('/pomodoro',[PomodoroController::class,'index'])->name('pomodoro');
Route::get('/pomodoro/analytics',[PomodoroController::class,'analytics'])->name('pomodoro.analytic');

Route::get('/admin/dashboard', function () {
    return Inertia::render('admin/Dashboard', ['cards' => Card::all()]);
});

// CARDS
Route::post('admin/card/set', [CardController::class, "setCard"])->name('card.set');
Route::post('admin/card/delete', [CardController::class, "delete"])->name('card.delete');


Route::middleware('auth')->group(function () {
    Route::prefix('pomodoro')->group(function () {
        Route::get('/', [PomodoroController::class, 'index'])->name('pomodoro');
        Route::get('/getcatalog', [CatalogController::class, 'getCatalog']);
        Route::post('/newfocus', [FocusSessionController::class, 'newFocus']);
    });
});

// AUTH
Route::get('auth/login', [AuthController::class, 'loginForm'])->name('auth.loginForm');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/logingoogle', [AuthController::class, 'loginGoogle'])->name('auth.loginGoogle');




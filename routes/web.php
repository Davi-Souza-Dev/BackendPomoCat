<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\CardController;
use App\Http\Controllers\PomodoroController;
use App\Http\Middleware\Auth;
use App\Models\Card;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return redirect()->route('pomodoro');
})->name('index');

Route::get('/catalogo', function () {
    return Inertia::render('Catalogo', ['cards' => Card::all()]);
});



// CARDS
Route::post('/card/set', [CardController::class, "setCard"])->name('card.set');
Route::post('/card/delete', [CardController::class, "delete"])->name('card.delete');

// POMODORO
Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro')->middleware(Auth::class);

// AUTH
Route::get('auth/login', [AuthController::class, 'loginForm'])->name('auth.loginForm');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/logingoogle', [AuthController::class, 'loginGoogle'])->name('auth.loginGoogle');

<?php

use App\Http\Controllers\App\AnalyticController;
use App\Http\Controllers\App\AudioController;
use App\Http\Controllers\App\CatalogController;
use App\Http\Controllers\App\FocusSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Dashboard\CardController;
use App\Http\Controllers\PomodoroController;
use App\Http\Middleware\AuthAdmin;
use App\Models\Card;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('pomodoro');
})->name('index');


Route::get('/pomodoro', [PomodoroController::class, 'index'])->name('pomodoro');
Route::get('/pomodoro/analytics', [AnalyticController::class, 'index'])->name('pomodoro.analytic');
Route::post("/pomodoro/analytic/dist/prevweek", [AnalyticController::class, 'distgraph']);
Route::post("/pomodoro/analytic/dist/nextweek", [AnalyticController::class, 'distgraph']);
Route::middleware('auth')->group(function () {
    Route::prefix('pomodoro')->group(function () {
        Route::get('/', [PomodoroController::class, 'index'])->name('pomodoro');
        // Route::get('/getcatalog', [CatalogController::class, 'getCatalog']);
        Route::post('/newfocus', [FocusSessionController::class, 'newFocus']);
    });

    Route::prefix('audio')->group(function(){
        Route::post('/setaudio',[AudioController::class,'setAudio'])->name('audio.set');
        Route::get('/getplaylist',[AudioController::class,'getPlaylist'])->name('audio.getPlaylist');
        Route::post('/delete',[AudioController::class,'delete'])->name('audio.delete');
        Route::post('/reorder',[AudioController::class,'reorder'])->name('audio.reorder');

    });
});

Route::middleware(AuthAdmin::class)->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', function () {
            return Inertia::render('admin/Dashboard', ['cards' => Card::all()]);
        });

        // CARDS
        Route::post('/card/set', [CardController::class, "setCard"])->name('card.set');
        Route::post('/card/delete', [CardController::class, "delete"])->name('card.delete');
    });
});


// AUTH
Route::get('auth/login', [AuthController::class, 'loginForm'])->name('auth.loginForm');
Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/logingoogle', [AuthController::class, 'loginGoogle'])->name('auth.loginGoogle');
Route::get('auth/register', [AuthController::class, 'registerForm'])->name('auth.registerForm');
Route::post('auth/register', [AuthController::class, 'register'])->name('auth.register');

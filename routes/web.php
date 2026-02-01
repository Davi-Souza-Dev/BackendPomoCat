<?php

use App\Http\Controllers\Dashboard\CardController;
use App\Models\Card;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return Inertia::render('Index');
})->name('index');

Route::get('/catalogo',function(){
    return Inertia::render('Catalogo',['cards'=>Card::all()]);
});



// CARDS
Route::post('/card/set',[CardController::class,"setCard"])->name('card.set');
Route::post('/card/delete',[CardController::class,"delete"])->name('card.delete');



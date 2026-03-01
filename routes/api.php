<?php

use App\Http\Controllers\Api\FocusSessionController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/newfocus/{username}',[FocusSessionController::class,'newFocus']);
Route::get('/getcatalog/{username}',[CatalogController::class,'getCatalog']);
Route::get('/getcard/{username}',[FocusSessionController::class,'getCard']);




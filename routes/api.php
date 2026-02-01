<?php

use App\Http\Controllers\Api\FocusSessionController;
use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\CatalogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/auth/login',[LoginController::class,'login']);
Route::post('/auth/logingoogle',[LoginController::class,'loginGoogle']);


Route::post('/newfocus',[FocusSessionController::class,'newFocus']);
Route::get('/getcatalog',[CatalogController::class,'getCatalog']);
Route::get('/getcard',[FocusSessionController::class,'getCard']);



// DASHBOARD




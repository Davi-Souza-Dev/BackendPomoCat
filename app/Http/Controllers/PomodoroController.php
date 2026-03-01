<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Throwable;

class PomodoroController
{
 public function index(){
    try{
        $user = Auth::user();
        return Inertia::render('Index',['user'=>$user]);
    }catch(Throwable $error){
        return redirect()->route('auth.loginForm');
    }
 }
}

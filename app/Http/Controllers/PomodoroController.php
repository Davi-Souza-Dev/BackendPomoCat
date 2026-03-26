<?php

namespace App\Http\Controllers;

use App\Actions\Analytic\GetTodayFocus;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Throwable;

class PomodoroController
{
    public function index(GetTodayFocus $getTodayFocus)
    {
        try{
            return Inertia::render('Index',['todayfocus' => $getTodayFocus->execute(Auth::user())]);
        }catch(Throwable $error){
            return redirect()->route('auth.loginForm');
        }
    }

    public function analytics(){
        return Inertia::render('Analytic');
    }
}

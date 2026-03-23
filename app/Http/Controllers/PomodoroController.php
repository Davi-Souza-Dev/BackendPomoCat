<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class PomodoroController
{
    public function index()
    {
        return Inertia::render('Index');
    }

    public function analytics(){
        return Inertia::render('Analytic');
    }
}

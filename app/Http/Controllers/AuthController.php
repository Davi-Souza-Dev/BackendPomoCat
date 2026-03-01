<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Throwable;

class AuthController
{
    public function loginForm()
    {
        return Inertia::render('auth/Login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route('pomodoro');
        }

        return back()->withErrors([
            'email' => 'Email ou senha inválidos',
        ]);
    }

    public function loginGoogle(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();

                return redirect()->route('pomodoro');
            } else {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'photo' => $request->photo
                ]);

                return redirect()->route('pomodoro');
            }
        } catch (Throwable $error) {
            dd($error);
            return [
                "error" => [
                    'title' => $error->getMessage(),
                ]
            ];
        }
    }
}

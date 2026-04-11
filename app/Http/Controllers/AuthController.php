<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\FormRegisterRequest;
use App\Models\User;
use Exception;
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
        Auth::logout();
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
            'message' => 'Email ou senha inválidos',
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

                Auth::login($user);

                return redirect()->route('pomodoro');
            }
        } catch (Exception $error) {
            return [
                "error" => $error->getMessage(),
            ];
        }
    }

    public function registerForm()
    {
        Auth::logout();
        return Inertia::render('auth/Register');
    }

    public function register(FormRegisterRequest $request)
    {
        try {
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            sleep(1);
            return redirect()->route('pomodoro');
        } catch (Exception $error) {
            return $error->getMessage();
        }
    }
}

<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Throwable;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $token = auth()->guard('api')->attempt($credentials);

        if ($token != null) {
            return response()->json([
                "success" => [
                    'username' => auth()->guard('api')->user()->username,
                    'email' => auth()->guard('api')->user()->email,
                    'token' => $token,
                    'photo' => $request->photo

                ]
            ]);
        } else {
            return [
                "error" => [
                    'title' => "Email ou Senha Invalidos",
                ]
            ];
        }
    }

    public function loginGoogle(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'email'],
                'password' => ['required'],
            ]);
            $token = auth()->guard('api')->attempt($credentials);
            if ($token != null) {
                return response()->json([
                    "success" => [
                        'username' => auth()->guard('api')->user()->username,
                        'email' => auth()->guard('api')->user()->email,
                        'token' => $token,
                        'photo' => auth()->guard('api')->user()->photo

                    ]
                ]);
            } else {
                $user = User::create([
                    'username' => $request->username,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'photo' => $request->photo
                ]);

                return response()->json([
                    "success" => [
                        "title" => "usuario criado!"
                    ]
                ]);
            }
        } catch (Throwable $error) {
            return [
                "error" => [
                    'title' => $error->getMessage(),
                ]
            ];
        }
    }
}

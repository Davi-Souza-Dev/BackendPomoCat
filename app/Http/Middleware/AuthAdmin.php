<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Event\Code\Throwable;
use Symfony\Component\HttpFoundation\Response;

class AuthAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = Auth::user();
            if ($user != null && $user->admin == true) {
                return $next($request);
            }
            return redirect()->route('auth.loginForm');
        } catch (Throwable $error) {
            return redirect()->route('auth.loginForm');
        }
    }
}

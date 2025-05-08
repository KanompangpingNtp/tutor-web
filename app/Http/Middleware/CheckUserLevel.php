<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserLevel
{
      /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $level
     */
    public function handle(Request $request, Closure $next, $level): Response
    {
        if (Auth::check() && Auth::user()->level === $level) {
            return $next($request);
        }

        return redirect()->route('LoginPage')->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
    }
}

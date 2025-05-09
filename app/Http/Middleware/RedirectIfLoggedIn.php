<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfLoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        // ถ้า user login แล้ว จะถูก redirect ไปหน้า home
        if (Auth::check()) {
            return redirect('/home');
        }

        return $next($request);
    }
}

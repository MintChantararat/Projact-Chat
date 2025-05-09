<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFirebaseAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!session()->has('employee_id')) {
            return redirect('/'); // ถ้าไม่มี session employee_id ให้กลับไปหน้า login
        }

        return $next($request);
    }
}
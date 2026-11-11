<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth('admin')->check()) {
            return redirect()->route('admin.login')->with('error', 'Please login to access admin panel.');
        }

        if (!auth('admin')->user()->is_active) {
            auth('admin')->logout();
            return redirect()->route('admin.login')->with('error', 'Your account has been deactivated.');
        }

        return $next($request);
    }
}

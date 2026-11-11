<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.login');
        }

        // Super Admin has unrestricted access
        if ($admin->isSuperAdmin()) {
            return $next($request);
        }

        // Check if admin has at least one of the specified permissions
        if (!empty($permissions)) {
            if (!$admin->hasAnyPermission($permissions)) {
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Unauthorized. Insufficient permissions.'], 403);
                }
                return abort(403, 'আপনার এই পেজ বা ফিচারে প্রবেশের অনুমতি (Permission) নেই।');
            }
        }

        return $next($request);
    }
}

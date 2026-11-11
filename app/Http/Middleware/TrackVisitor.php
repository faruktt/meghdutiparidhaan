<?php

namespace App\Http\Middleware;

use App\Models\Visitor;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only track frontend GET requests (excluding admin, api, ajax, assets)
        if ($request->isMethod('GET') && !$request->ajax() && !$request->wantsJson()) {
            $path = $request->path();
            if (!str_starts_with($path, 'admin') && 
                !str_starts_with($path, 'api') && 
                !str_starts_with($path, '_') && 
                !str_starts_with($path, 'up')) {
                try {
                    Visitor::recordVisit($request);
                } catch (\Throwable $e) {
                    // Silently ignore tracking errors so visitor experience is unaffected
                }
            }
        }

        return $response;
    }
}


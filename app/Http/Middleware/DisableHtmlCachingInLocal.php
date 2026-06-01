<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DisableHtmlCachingInLocal
{
    /**
     * Prevent the browser from keeping a stale HTML shell that still points at an old Vite chunk (404 → blank Inertia page).
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (! app()->isLocal()) {
            return $response;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        if (! str_contains($contentType, 'text/html')) {
            return $response;
        }

        $response->headers->set('Cache-Control', 'private, no-store, no-cache, must-revalidate');
        $response->headers->set('Pragma', 'no-cache');
        $response->headers->set('Expires', '0');

        return $response;
    }
}

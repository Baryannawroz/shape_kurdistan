<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasAnyRole(['super-admin', 'admin', 'editor'])) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $next($request);
    }
}

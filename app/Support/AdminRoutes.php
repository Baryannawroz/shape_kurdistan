<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class AdminRoutes
{
    /**
     * @return array<string, string>
     */
    public static function uriPatterns(): array
    {
        return Cache::remember('admin_route_uri_patterns', 3600, function (): array {
            $patterns = [];

            foreach (Route::getRoutes() as $route) {
                $name = $route->getName();

                if (! is_string($name)) {
                    continue;
                }

                if (! str_starts_with($name, 'admin.') && ! in_array($name, ['logout', 'site.home'], true)) {
                    continue;
                }

                $patterns[$name] = '/'.ltrim($route->uri(), '/');
            }

            return $patterns;
        });
    }

    public static function forgetCache(): void
    {
        Cache::forget('admin_route_uri_patterns');
    }
}

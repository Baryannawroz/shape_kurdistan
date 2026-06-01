<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locales = array_keys(config('app.locales', []));
        $locale = $request->route('locale');

        if ($locale && in_array($locale, $locales, true)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } elseif (Session::has('locale') && in_array((string) Session::get('locale'), $locales, true)) {
            App::setLocale((string) Session::get('locale'));
        } else {
            App::setLocale(config('app.fallback_locale'));
        }

        return $next($request);
    }
}

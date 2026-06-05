<?php

namespace App\Http\Middleware;

use App\Support\AdminEditLinks;
use App\Support\RichContent;
use App\Support\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Align Laravel’s locale with the URL prefix before building shared props.
     * (The `web` stack runs this middleware before the route group’s `set.locale`.)
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $localesConfig = config('app.locales', []);
        $validLocaleKeys = array_keys($localesConfig);
        $routeLocale = $request->route('locale');

        if (is_string($routeLocale) && in_array($routeLocale, $validLocaleKeys, true)) {
            App::setLocale($routeLocale);
        }

        $locale = app()->getLocale();
        $direction = $localesConfig[$locale]['dir'] ?? 'ltr';

        return [
            ...parent::share($request),
            'auth' => [
                'user' => fn () => $request->user() ? [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                    'roles' => $request->user()->getRoleNames()->values()->all(),
                ] : null,
            ],
            'canManageSite' => fn () => AdminEditLinks::canManage($request->user()),
            'adminEdits' => function () use ($request) {
                if (! $request->routeIs('admin.*')) {
                    return [];
                }

                return AdminEditLinks::build($request->user(), AdminEditLinks::forAdminArea());
            },
            'locale' => $locale,
            'direction' => $direction,
            'locales' => $localesConfig,
            'siteName' => SiteSettings::get('general.site_name_'.$locale) ?? config('app.name'),
            'navLinks' => [
                ['label' => __('nav.home'), 'route' => 'site.home', 'params' => []],
                ['label' => __('nav.about'), 'route' => 'site.about', 'params' => []],
                ['label' => __('nav.services'), 'route' => 'site.services', 'params' => []],
                ['label' => __('nav.products'), 'route' => 'site.products', 'params' => []],
                ['label' => __('nav.portfolio'), 'route' => 'site.portfolio', 'params' => []],
                ['label' => __('nav.contact'), 'route' => 'site.contact', 'params' => []],
            ],
            'footerData' => [
                'address' => RichContent::expand(SiteSettings::get('contact.address_'.$locale) ?? ''),
                'phone' => SiteSettings::get('contact.phone'),
                'email' => SiteSettings::get('contact.email'),
            ],
            'socialLinks' => [
                'facebook' => SiteSettings::get('social.facebook'),
                'twitter' => SiteSettings::get('social.twitter'),
                'instagram' => SiteSettings::get('social.instagram'),
                'linkedin' => SiteSettings::get('social.linkedin'),
                'youtube' => SiteSettings::get('social.youtube'),
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'ziggy' => fn () => array_merge((new Ziggy)->toArray(), [
                'location' => $request->url(),
            ]),
        ];
    }
}

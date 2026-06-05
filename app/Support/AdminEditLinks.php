<?php

namespace App\Support;

use App\Models\Page;
use App\Models\User;
use Illuminate\Support\Facades\Route;

class AdminEditLinks
{
    public static function canManage(?User $user): bool
    {
        return $user !== null && $user->hasAnyRole(['super-admin', 'admin', 'editor']);
    }

    /**
     * @param  array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>  $links
     * @return array<int, array{label: string, href: string, primary: bool}>
     */
    public static function build(?User $user, array $links): array
    {
        if (! self::canManage($user)) {
            return [];
        }

        return collect($links)
            ->filter(fn (array $link): bool => Route::has($link['route']))
            ->map(fn (array $link): array => [
                'label' => $link['label'],
                'href' => route($link['route'], $link['params'] ?? []),
                'primary' => (bool) ($link['primary'] ?? false),
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forHome(): array
    {
        return [
            ['label' => 'Site settings', 'route' => 'admin.cms.site-settings.index', 'primary' => true],
            ['label' => 'Services', 'route' => 'admin.cms.services.index'],
            ['label' => 'Projects', 'route' => 'admin.cms.projects.index'],
            ['label' => 'Team', 'route' => 'admin.cms.team-members.index'],
            ['label' => 'Testimonials', 'route' => 'admin.cms.testimonials.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forAbout(?int $pageId): array
    {
        $links = [
            ['label' => 'Manage team', 'route' => 'admin.cms.team-members.index'],
        ];

        if ($pageId !== null) {
            array_unshift($links, [
                'label' => 'Edit about page',
                'route' => 'admin.cms.pages.edit',
                'params' => ['page' => $pageId],
                'primary' => true,
            ]);
        } else {
            $links[] = ['label' => 'Pages', 'route' => 'admin.cms.pages.index'];
        }

        return $links;
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forContact(): array
    {
        return [
            ['label' => 'Full contact settings', 'route' => 'admin.cms.contact-settings.edit'],
            ['label' => 'Messages', 'route' => 'admin.cms.messages.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forServicesIndex(): array
    {
        return [
            ['label' => 'Add service', 'route' => 'admin.cms.services.create', 'primary' => true],
            ['label' => 'Manage services', 'route' => 'admin.cms.services.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forServiceShow(int $serviceId): array
    {
        return [
            [
                'label' => 'Edit this service',
                'route' => 'admin.cms.services.edit',
                'params' => ['service' => $serviceId],
                'primary' => true,
            ],
            ['label' => 'All services', 'route' => 'admin.cms.services.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forProductsIndex(): array
    {
        return [
            ['label' => 'Add product', 'route' => 'admin.cms.products.create', 'primary' => true],
            ['label' => 'Manage products', 'route' => 'admin.cms.products.index'],
            ['label' => 'Categories', 'route' => 'admin.cms.product-categories.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forProductShow(int $productId): array
    {
        return [
            [
                'label' => 'Edit this product',
                'route' => 'admin.cms.products.edit',
                'params' => ['product' => $productId],
                'primary' => true,
            ],
            ['label' => 'All products', 'route' => 'admin.cms.products.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forPortfolioIndex(): array
    {
        return [
            ['label' => 'Add project', 'route' => 'admin.cms.projects.create', 'primary' => true],
            ['label' => 'Manage projects', 'route' => 'admin.cms.projects.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forProjectShow(int $projectId): array
    {
        return [
            [
                'label' => 'Edit this project',
                'route' => 'admin.cms.projects.edit',
                'params' => ['project' => $projectId],
                'primary' => true,
            ],
            ['label' => 'All projects', 'route' => 'admin.cms.projects.index'],
        ];
    }

    /**
     * @return array<int, array{label: string, route: string, params?: array<string, mixed>, primary?: bool}>
     */
    public static function forAdminArea(): array
    {
        $locale = app()->getLocale();

        return [
            ['label' => 'View site', 'route' => 'site.home', 'params' => ['locale' => $locale], 'primary' => true],
            ['label' => 'Contact page', 'route' => 'site.contact', 'params' => ['locale' => $locale]],
            ['label' => 'Site settings', 'route' => 'admin.cms.site-settings.index'],
            ['label' => 'Services', 'route' => 'admin.cms.services.index'],
            ['label' => 'Products', 'route' => 'admin.cms.products.index'],
            ['label' => 'Projects', 'route' => 'admin.cms.projects.index'],
        ];
    }

    /**
     * @return array<int, array{code: string, name: string}>
     */
    public static function localeMeta(): array
    {
        $locales = config('app.locales', []);

        return collect($locales)->map(fn (array $meta, string $code): array => [
            'code' => $code,
            'name' => (string) ($meta['name'] ?? $code),
        ])->values()->all();
    }

    /**
     * @return array<string, string>
     */
    public static function homeSettingsPayload(): array
    {
        $settings = [
            'appearance.stat_projects' => (string) (SiteSettings::get('appearance.stat_projects') ?? ''),
            'appearance.stat_clients' => (string) (SiteSettings::get('appearance.stat_clients') ?? ''),
            'appearance.stat_years' => (string) (SiteSettings::get('appearance.stat_years') ?? ''),
            'appearance.stat_awards' => (string) (SiteSettings::get('appearance.stat_awards') ?? ''),
        ];

        foreach (array_keys(config('app.locales', [])) as $locale) {
            $settings['appearance.hero_headline_'.$locale] = (string) (SiteSettings::get('appearance.hero_headline_'.$locale) ?? '');
            $settings['appearance.hero_subheadline_'.$locale] = (string) (SiteSettings::get('appearance.hero_subheadline_'.$locale) ?? '');
            $settings['appearance.hero_primary_cta_'.$locale] = (string) (SiteSettings::get('appearance.hero_primary_cta_'.$locale) ?? '');
            $settings['appearance.hero_secondary_cta_'.$locale] = (string) (SiteSettings::get('appearance.hero_secondary_cta_'.$locale) ?? '');
        }

        return array_merge(
            $settings,
            HomeSections::settingsPayload(),
            PageSeo::settingsPayload('home'),
        );
    }

    public static function pagePayload(?Page $page): ?array
    {
        if ($page === null) {
            return null;
        }

        $page->loadMissing('translations');

        $translations = [];
        foreach (array_keys(config('app.locales', [])) as $locale) {
            $row = $page->translations->firstWhere('locale', $locale);

            $translations[$locale] = [
                'locale' => $locale,
                'title' => $row?->title ?? '',
                'content' => $row?->content ?? '',
                'meta_title' => $row?->meta_title ?? '',
                'meta_description' => $row?->meta_description ?? '',
            ];
        }

        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'is_active' => (bool) $page->is_active,
            'order' => (int) $page->order,
            'translations' => $translations,
        ];
    }

    /**
     * @return array{phone: string, email: string, maps_embed_url: string, address: array<string, string>}|null
     */
    public static function contactSettingsPayload(): ?array
    {
        $address = [];
        foreach (array_keys(config('app.locales', [])) as $locale) {
            $address[$locale] = SiteSettings::get('contact.address_'.$locale) ?? '';
        }

        return [
            'phone' => SiteSettings::get('contact.phone') ?? '',
            'email' => SiteSettings::get('contact.email') ?? '',
            'maps_embed_url' => SiteSettings::get('contact.maps_embed_url') ?? '',
            'address' => $address,
        ];
    }
}

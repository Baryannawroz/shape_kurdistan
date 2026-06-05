<?php

namespace App\Support;

class PageSeo
{
    /**
     * @return array{title: string, description: string, keywords: string}
     */
    public static function resolve(string $pageKey, string $fallbackTitle = '', string $fallbackDescription = ''): array
    {
        $locale = app()->getLocale();

        $title = SiteSettings::get("seo.page.{$pageKey}.title_{$locale}")
            ?? SiteSettings::get("seo.default_title_{$locale}")
            ?? $fallbackTitle;

        $description = SiteSettings::get("seo.page.{$pageKey}.description_{$locale}")
            ?? SiteSettings::get("seo.default_description_{$locale}")
            ?? $fallbackDescription;

        return [
            'title' => (string) $title,
            'description' => (string) $description,
            'keywords' => (string) (SiteSettings::get('seo.keywords') ?? ''),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function settingsPayload(string $pageKey): array
    {
        $settings = [];
        foreach (array_keys(config('app.locales', [])) as $locale) {
            $settings["seo.page.{$pageKey}.title_{$locale}"] = SiteSettings::get("seo.page.{$pageKey}.title_{$locale}") ?? '';
            $settings["seo.page.{$pageKey}.description_{$locale}"] = SiteSettings::get("seo.page.{$pageKey}.description_{$locale}") ?? '';
        }
        $settings['seo.keywords'] = SiteSettings::get('seo.keywords') ?? '';

        return $settings;
    }

    /**
     * @return array<int, array{key: string, value: string, group: string}>
     */
    public static function seederRows(): array
    {
        $pages = [
            'home' => [
                'en' => ['title' => 'Shape Kurdistan — Digital Studio', 'description' => 'Strategy, design, and engineering for modern brands in Kurdistan and beyond.'],
                'ckb' => ['title' => 'شێپ کوردستان — ستۆدیۆی دیجیتاڵ', 'description' => 'ستراتیژی، دیزاین و ئەندازیاری بۆ براندە هەنووکەییەکان.'],
                'ar' => ['title' => 'شيب كردستان — استوديو رقمي', 'description' => 'استراتيجية وتصميم وهندسة للعلامات الحديثة.'],
            ],
            'about' => [
                'en' => ['title' => 'About — Shape Kurdistan', 'description' => 'Our story, team, and how we partner with organizations to ship digital products.'],
                'ckb' => ['title' => 'دەربارە — شێپ کوردستان', 'description' => 'چیرۆک و تیمەکەمان و چۆن لەگەڵ ڕێکخراوەکان کار دەکەین.'],
                'ar' => ['title' => 'من نحن — شيب كردستان', 'description' => 'قصتنا وفريقنا وكيف نتعاون مع المؤسسات.'],
            ],
            'contact' => [
                'en' => ['title' => 'Contact — Shape Kurdistan', 'description' => 'Get in touch for projects, partnerships, and support.'],
                'ckb' => ['title' => 'پەیوەندی — شێپ کوردستان', 'description' => 'پەیوەندی بکە بۆ پڕۆژە و هاوکاری.'],
                'ar' => ['title' => 'تواصل — شيب كردستان', 'description' => 'تواصل معنا للمشاريع والشراكات.'],
            ],
            'services' => [
                'en' => ['title' => 'Services — Shape Kurdistan', 'description' => 'Product strategy, UX, web development, and performance services.'],
                'ckb' => ['title' => 'خزمەتگوزاریەکان', 'description' => 'ستراتیژی بەرهەم، UX، گەشەپێدانی وێب و خزمەتگوزاری کارایی.'],
                'ar' => ['title' => 'الخدمات', 'description' => 'استراتيجية المنتج وتجربة المستخدم وتطوير الويب.'],
            ],
            'products' => [
                'en' => ['title' => 'Products — Shape Kurdistan', 'description' => 'Browse our product catalog and categories.'],
                'ckb' => ['title' => 'بەرهەمەکان', 'description' => 'کاتالۆگی بەرهەمەکانمان ببینە.'],
                'ar' => ['title' => 'المنتجات', 'description' => 'تصفح كتالوج منتجاتنا.'],
            ],
            'portfolio' => [
                'en' => ['title' => 'Portfolio — Shape Kurdistan', 'description' => 'Selected projects across strategy, design, and engineering.'],
                'ckb' => ['title' => 'پۆرتفۆلیۆ', 'description' => 'پڕۆژە هەڵبژێردراوەکان لە ستراتیژی و دیزاین و ئەندازیاری.'],
                'ar' => ['title' => 'معرض الأعمال', 'description' => 'مشاريع مختارة في الاستراتيجية والتصميم والهندسة.'],
            ],
        ];

        $rows = [];
        foreach ($pages as $pageKey => $byLocale) {
            foreach ($byLocale as $locale => $data) {
                $rows[] = ['key' => "seo.page.{$pageKey}.title_{$locale}", 'value' => $data['title'], 'group' => 'seo'];
                $rows[] = ['key' => "seo.page.{$pageKey}.description_{$locale}", 'value' => $data['description'], 'group' => 'seo'];
            }
        }

        return $rows;
    }
}

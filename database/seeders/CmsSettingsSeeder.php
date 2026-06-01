<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class CmsSettingsSeeder extends Seeder
{
    /**
     * @var array<int, array{key: string, value: string|null, group: string}>
     */
    private array $rows = [
        ['key' => 'general.site_name_en', 'value' => 'Acme Agency', 'group' => 'general'],
        ['key' => 'general.site_name_ar', 'value' => 'وكالة أكمي', 'group' => 'general'],
        ['key' => 'general.site_name_ckb', 'value' => 'ئەیجێنسی ئەکمێ', 'group' => 'general'],
        ['key' => 'general.about_excerpt_en', 'value' => 'Strategy, product design, and engineering for teams that care about craft, clarity, and conversion.', 'group' => 'general'],
        ['key' => 'general.about_excerpt_ar', 'value' => 'استراتيجية وتصميم منتج وهندسة للفرق التي تهتم بالجودة والوضوح.', 'group' => 'general'],
        ['key' => 'general.about_excerpt_ckb', 'value' => 'ستراتیژی، دیزاینی بەرهەم و ئەندازیاری بۆ تیمەکان.', 'group' => 'general'],
        ['key' => 'contact.phone', 'value' => '+1 (555) 010-0000', 'group' => 'contact'],
        ['key' => 'contact.email', 'value' => 'hello@example.com', 'group' => 'contact'],
        ['key' => 'contact.address_en', 'value' => '123 Business Ave, Suite 100', 'group' => 'contact'],
        ['key' => 'contact.address_ar', 'value' => '١٢٣ شارع الأعمال', 'group' => 'contact'],
        ['key' => 'contact.address_ckb', 'value' => '١٢٣ شەقامی بازرگانی', 'group' => 'contact'],
        ['key' => 'contact.maps_embed_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3022.184132576998!2d-73.98811768459398!3d40.75889597932681!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25855c6480299%3A0x55194ec5a1ae722e!2sTimes%20Square!5e0!3m2!1sen!2sus!4v1610000000000!5m2!1sen!2sus', 'group' => 'contact'],
        ['key' => 'social.facebook', 'value' => 'https://facebook.com', 'group' => 'social'],
        ['key' => 'social.twitter', 'value' => 'https://twitter.com', 'group' => 'social'],
        ['key' => 'social.instagram', 'value' => 'https://instagram.com', 'group' => 'social'],
        ['key' => 'social.linkedin', 'value' => 'https://linkedin.com', 'group' => 'social'],
        ['key' => 'social.youtube', 'value' => 'https://youtube.com', 'group' => 'social'],
        ['key' => 'seo.default_title_en', 'value' => 'Acme Agency — Digital Studio', 'group' => 'seo'],
        ['key' => 'seo.default_title_ar', 'value' => 'وكالة أكمي — استوديو رقمي', 'group' => 'seo'],
        ['key' => 'seo.default_description_en', 'value' => 'Corporate and portfolio website built with Laravel and Vue.', 'group' => 'seo'],
        ['key' => 'seo.default_description_ar', 'value' => 'موقع شركة ومعرض أعمال مبني بلارافيل وفيو.', 'group' => 'seo'],
        ['key' => 'seo.default_description_ckb', 'value' => 'ماڵپەڕی کۆمپانیا و پۆرتفۆلیۆ لە Laravel و Vue.', 'group' => 'seo'],
        ['key' => 'seo.keywords', 'value' => 'agency, laravel, vue, portfolio', 'group' => 'seo'],
        ['key' => 'appearance.primary_color', 'value' => '#2563eb', 'group' => 'appearance'],
        ['key' => 'appearance.secondary_color', 'value' => '#64748b', 'group' => 'appearance'],
        ['key' => 'appearance.font_choice', 'value' => 'inter', 'group' => 'appearance'],
        ['key' => 'appearance.hero_headline_en', 'value' => 'We craft digital experiences', 'group' => 'appearance'],
        ['key' => 'appearance.hero_headline_ar', 'value' => 'نصم تجارب رقمية متميزة', 'group' => 'appearance'],
        ['key' => 'appearance.hero_headline_ckb', 'value' => 'ئێمە ئەزموونی دیجیتاڵ دروست دەکەین', 'group' => 'appearance'],
        ['key' => 'appearance.hero_subheadline_en', 'value' => 'Strategy, design, and engineering for modern brands.', 'group' => 'appearance'],
        ['key' => 'appearance.hero_subheadline_ar', 'value' => 'استراتيجية وتصميم وهندسة للعلامات الحديثة.', 'group' => 'appearance'],
        ['key' => 'appearance.hero_subheadline_ckb', 'value' => 'ستراتیژی، دیزاین و ئەندازیاری.', 'group' => 'appearance'],
        ['key' => 'appearance.hero_primary_cta_en', 'value' => 'Our work', 'group' => 'appearance'],
        ['key' => 'appearance.hero_primary_cta_ar', 'value' => 'أعمالنا', 'group' => 'appearance'],
        ['key' => 'appearance.hero_primary_cta_ckb', 'value' => 'کارەکانمان', 'group' => 'appearance'],
        ['key' => 'appearance.hero_secondary_cta_en', 'value' => 'Contact', 'group' => 'appearance'],
        ['key' => 'appearance.hero_secondary_cta_ar', 'value' => 'تواصل', 'group' => 'appearance'],
        ['key' => 'appearance.hero_secondary_cta_ckb', 'value' => 'پەیوەندی', 'group' => 'appearance'],
        ['key' => 'appearance.stat_projects', 'value' => '186', 'group' => 'appearance'],
        ['key' => 'appearance.stat_clients', 'value' => '72', 'group' => 'appearance'],
        ['key' => 'appearance.stat_years', 'value' => '14', 'group' => 'appearance'],
        ['key' => 'appearance.stat_awards', 'value' => '11', 'group' => 'appearance'],
        ['key' => 'appearance.show_blog_teaser', 'value' => '0', 'group' => 'appearance'],
    ];

    public function run(): void
    {
        foreach ($this->rows as $row) {
            Setting::query()->updateOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'], 'group' => $row['group']]
            );
        }
    }
}

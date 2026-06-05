<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Support\HomeSections;
use App\Support\PageSeo;
use Database\Seeders\Support\SeederMedia;
use Illuminate\Database\Seeder;

class CmsSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $media = SeederMedia::paths();

        /** @var array<int, array{key: string, value: string|null, group: string}> $rows */
        $rows = [
            ['key' => 'general.site_name_en', 'value' => 'Shape Kurdistan', 'group' => 'general'],
            ['key' => 'general.site_name_ar', 'value' => 'شيب كردستان', 'group' => 'general'],
            ['key' => 'general.site_name_ckb', 'value' => 'شێپ کوردستان', 'group' => 'general'],
            ['key' => 'general.about_excerpt_en', 'value' => '<p>We partner with organizations across Kurdistan and the wider region to design, build, and launch digital products that feel premium, load fast, and convert.</p>', 'group' => 'general'],
            ['key' => 'general.about_excerpt_ar', 'value' => '<p>نتعاون مع المؤسسات في كردستان والمنطقة لتصميم وبناء وإطلاق منتجات رقمية سريعة وعالية الجودة.</p>', 'group' => 'general'],
            ['key' => 'general.about_excerpt_ckb', 'value' => '<p>لەگەڵ ڕێکخراوەکان لە کوردستان و ناوچەکەدا کار دەکەین بۆ دیزاین و بەرهەمهێنان و بڵاوکردنەوەی بەرهەمی دیجیتاڵی کوالیتی بەرز.</p>', 'group' => 'general'],

            ['key' => 'contact.phone', 'value' => '+964 750 123 4567', 'group' => 'contact'],
            ['key' => 'contact.email', 'value' => 'hello@shapekurdistan.com', 'group' => 'contact'],
            ['key' => 'contact.address_en', 'value' => '<p><strong>Shape Kurdistan Studio</strong><br>English Village, Erbil<br>Kurdistan Region, Iraq</p>', 'group' => 'contact'],
            ['key' => 'contact.address_ar', 'value' => '<p><strong>استوديو شيب كردستان</strong><br>القرية الإنجليزية، أربيل<br>إقليم كردستان العراق</p>', 'group' => 'contact'],
            ['key' => 'contact.address_ckb', 'value' => '<p><strong>ستۆدیۆی شێپ کوردستان</strong><br>گوندی ئینگلیزی، هەولێر<br>هەرێمی کوردستان، عێراق</p>', 'group' => 'contact'],
            ['key' => 'contact.maps_embed_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d26829.5!2d44.009!3d36.191!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x400722fe13413463%3A0xc000f1f6c6c6c6c6!2sErbil%2C%20Iraq!5e0!3m2!1sen!2s!4v1700000000000!5m2!1sen!2s', 'group' => 'contact'],

            ['key' => 'social.facebook', 'value' => 'https://facebook.com/shapekurdistan', 'group' => 'social'],
            ['key' => 'social.instagram', 'value' => 'https://instagram.com/shapekurdistan', 'group' => 'social'],
            ['key' => 'social.linkedin', 'value' => 'https://linkedin.com/company/shapekurdistan', 'group' => 'social'],
            ['key' => 'social.youtube', 'value' => 'https://youtube.com/@shapekurdistan', 'group' => 'social'],
            ['key' => 'social.twitter', 'value' => 'https://x.com/shapekurdistan', 'group' => 'social'],

            ['key' => 'seo.default_title_en', 'value' => 'Shape Kurdistan — Digital Studio', 'group' => 'seo'],
            ['key' => 'seo.default_title_ar', 'value' => 'شيب كردستان — استوديو رقمي', 'group' => 'seo'],
            ['key' => 'seo.default_title_ckb', 'value' => 'شێپ کوردستان — ستۆدیۆی دیجیتاڵ', 'group' => 'seo'],
            ['key' => 'seo.default_description_en', 'value' => 'Strategy, UX, and engineering for brands in Kurdistan. Websites, apps, and platforms built to perform.', 'group' => 'seo'],
            ['key' => 'seo.default_description_ar', 'value' => 'استراتيجية وتجربة مستخدم وهندسة للعلامات في كردستان. مواقع وتطبيقات عالية الأداء.', 'group' => 'seo'],
            ['key' => 'seo.default_description_ckb', 'value' => 'ستراتیژی، UX و ئەندازیاری بۆ براندەکان لە کوردستان. ماڵپەڕ و ئەپڵیکەیشنی خێرا و کوالیتی بەرز.', 'group' => 'seo'],
            ['key' => 'seo.keywords', 'value' => 'Shape Kurdistan, web design Erbil, digital agency Kurdistan, UX, Laravel, branding', 'group' => 'seo'],

            ['key' => 'appearance.primary_color', 'value' => '#2563eb', 'group' => 'appearance'],
            ['key' => 'appearance.secondary_color', 'value' => '#64748b', 'group' => 'appearance'],
            ['key' => 'appearance.font_choice', 'value' => 'inter', 'group' => 'appearance'],
            ['key' => 'appearance.hero_image', 'value' => $media['hero'], 'group' => 'appearance'],
            ['key' => 'appearance.hero_headline_en', 'value' => 'Shape bold digital experiences for Kurdistan and beyond', 'group' => 'appearance'],
            ['key' => 'appearance.hero_headline_ar', 'value' => 'اصنع تجارب رقمية جريئة لكردستان وما بعدها', 'group' => 'appearance'],
            ['key' => 'appearance.hero_headline_ckb', 'value' => 'ئەزموونی دیجیتاڵی بەهێز بۆ کوردستان و دەرەوەی', 'group' => 'appearance'],
            ['key' => 'appearance.hero_subheadline_en', 'value' => '<p>From first workshop to launch day, we help teams ship <strong>marketing sites</strong>, <strong>customer portals</strong>, and <strong>product platforms</strong> with clarity, craft, and measurable results.</p>', 'group' => 'appearance'],
            ['key' => 'appearance.hero_subheadline_ar', 'value' => '<p>من أول ورشة عمل حتى يوم الإطلاق، نساعد الفرق على إطلاق <strong>مواقع تسويقية</strong> و<strong>بوابات عملاء</strong> و<strong>منصات منتجات</strong> بجودة عالية ونتائج قابلة للقياس.</p>', 'group' => 'appearance'],
            ['key' => 'appearance.hero_subheadline_ckb', 'value' => '<p>لە یەکەم وۆرکشۆپەوە تا ڕۆژی بڵاوکردنەوە، یارمەتی تیمەکان دەدەین <strong>ماڵپەڕی بازرگانی</strong>، <strong>دەروازەی کڕیار</strong> و <strong>پلاتفۆرمی بەرهەم</strong> بە کوالیتی بەرز بگەیەنن.</p>', 'group' => 'appearance'],
            ['key' => 'appearance.hero_primary_cta_en', 'value' => 'Start a project', 'group' => 'appearance'],
            ['key' => 'appearance.hero_primary_cta_ar', 'value' => 'ابدأ مشروعاً', 'group' => 'appearance'],
            ['key' => 'appearance.hero_primary_cta_ckb', 'value' => 'پڕۆژەکەت دەست پێ بکە', 'group' => 'appearance'],
            ['key' => 'appearance.hero_secondary_cta_en', 'value' => 'View our work', 'group' => 'appearance'],
            ['key' => 'appearance.hero_secondary_cta_ar', 'value' => 'شاهد أعمالنا', 'group' => 'appearance'],
            ['key' => 'appearance.hero_secondary_cta_ckb', 'value' => 'کارەکانمان ببینە', 'group' => 'appearance'],
            ['key' => 'appearance.stat_projects', 'value' => '120', 'group' => 'appearance'],
            ['key' => 'appearance.stat_clients', 'value' => '48', 'group' => 'appearance'],
            ['key' => 'appearance.stat_years', 'value' => '8', 'group' => 'appearance'],
            ['key' => 'appearance.stat_awards', 'value' => '6', 'group' => 'appearance'],
            ['key' => 'appearance.show_blog_teaser', 'value' => '0', 'group' => 'appearance'],
        ];

        foreach (array_merge($rows, HomeSections::seederRows(), PageSeo::seederRows()) as $row) {
            Setting::query()->updateOrCreate(
                ['key' => $row['key']],
                ['value' => $row['value'], 'group' => $row['group']]
            );
        }
    }
}

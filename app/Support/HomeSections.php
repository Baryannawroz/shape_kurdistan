<?php

namespace App\Support;

class HomeSections
{
    /**
     * @return array<string, list<string>>
     */
    public static function fieldMap(): array
    {
        return [
            'why_choose' => ['eyebrow', 'title', 'lead'],
            'vision' => ['eyebrow', 'title', 'lead', 'body'],
            'mission' => ['eyebrow', 'title', 'lead', 'body'],
            'benefits' => ['eyebrow', 'title', 'lead'],
            'process' => ['eyebrow', 'title', 'lead'],
            'portfolio' => ['eyebrow', 'title', 'lead'],
            'pricing' => ['eyebrow', 'title', 'lead'],
            'faq' => ['eyebrow', 'title', 'lead'],
            'reviews' => ['eyebrow', 'title', 'lead'],
            'takeaways' => ['eyebrow', 'title', 'lead'],
            'final_cta' => ['title', 'lead'],
            'team' => ['eyebrow', 'title'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function settingsPayload(): array
    {
        $settings = [];
        $locales = array_keys(config('app.locales', []));

        foreach (self::fieldMap() as $section => $fields) {
            foreach ($fields as $field) {
                foreach ($locales as $locale) {
                    $key = self::settingKey($section, $field, $locale);
                    $settings[$key] = SiteSettings::get($key) ?? self::defaultValue($section, $field, $locale);
                }
            }
        }

        return $settings;
    }

    /**
     * @return array<string, array<string, string|null>>
     */
    public static function forLocale(?string $locale = null): array
    {
        $locale ??= app()->getLocale();
        $sections = [];

        foreach (self::fieldMap() as $section => $fields) {
            $sections[$section] = [];
            foreach ($fields as $field) {
                $raw = SiteSettings::get(self::settingKey($section, $field, $locale))
                    ?? self::defaultValue($section, $field, $locale);
                $sections[$section][$field] = in_array($field, ['lead', 'body'], true)
                    ? RichContent::expand($raw ?? '')
                    : $raw;
            }
        }

        return $sections;
    }

    public static function settingKey(string $section, string $field, string $locale): string
    {
        return "home.{$section}_{$field}_{$locale}";
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public static function defaultCatalog(): array
    {
        return [
            'why_choose' => [
                'eyebrow' => ['en' => 'Our advantages', 'ckb' => 'سوودەکانمان', 'ar' => 'مزايانا'],
                'title' => ['en' => 'Why teams choose Shape Kurdistan', 'ckb' => 'بۆچی تیمەکان شێپ کوردستان هەڵدەبژێرن', 'ar' => 'لماذا تختار الفرق شيب كردستان'],
                'lead' => [
                    'en' => '<p>We run <strong>focused engagements</strong> from discovery through launch — with bilingual content workflows, clear milestones, and outcomes you can present to leadership.</p>',
                    'ckb' => '<p><strong>کارگەی پوخت</strong> لە دۆزینەوە تا بڵاوکردنەوە — ناوەڕۆکی دوو زمانە، قۆناغی ڕوون و ئەنجامی پێوانەکراو.</p>',
                    'ar' => '<p>نقود <strong>شراكات مركّزة</strong> من الاكتشاف حتى الإطلاق — مع محتوى ثنائي اللغة ومعالم واضحة ونتائج قابلة للقياس.</p>',
                ],
            ],
            'vision' => [
                'eyebrow' => ['en' => 'Vision', 'ckb' => 'بینین', 'ar' => 'الرؤية'],
                'title' => ['en' => 'A digital standard for the region', 'ckb' => 'ستانداردێکی دیجیتاڵ بۆ ناوچەکە', 'ar' => 'معيار رقمي للمنطقة'],
                'lead' => [
                    'en' => '<p>Make world-class product craft accessible to organizations rooted in Kurdistan — without compromising speed or maintainability.</p>',
                    'ckb' => '<p>کاری بەرهەمی جیهانی بۆ ڕێکخراوەکانی کوردستان بەردەست بکەین — بەبێ لەدەستدانی خێرایی یان کوالیتی.</p>',
                    'ar' => '<p>جعل جودة المنتجات العالمية متاحة للمؤسسات في كردستان — دون التضحية بالسرعة أو قابلية الصيانة.</p>',
                ],
                'body' => [
                    'en' => '<p>We see a future where local brands compete on experience, performance, and trust — supported by design systems, modern engineering, and teams that stay after launch.</p>',
                    'ckb' => '<p>داهاتووێک دەبینین کە براندە ناوخۆییەکان لە ئەزموون و کارایی و متمانەدا پێشبکەون — بە سیستەمی دیزاین و ئەندازیاری نوێ.</p>',
                    'ar' => '<p>نرى مستقبلاً تتنافس فيه العلامات المحلية في التجربة والأداء والثقة — مدعومة بأنظمة تصميم وهندسة حديثة.</p>',
                ],
            ],
            'mission' => [
                'eyebrow' => ['en' => 'Mission', 'ckb' => 'ئامانج', 'ar' => 'المهمة'],
                'title' => ['en' => 'Ship work you are proud to show', 'ckb' => 'کارێک بگەیەنە کە شەرەف بێت پیشانی بدەیت', 'ar' => 'أطلق عملاً تفخر بعرضه'],
                'lead' => [
                    'en' => '<p>Embed with your team to <strong>design, build, and launch</strong> websites and platforms that convert visitors into customers.</p>',
                    'ckb' => '<p>لەگەڵ تیمەکەتدا <strong>دیزاین، دروستکردن و بڵاوکردنەوە</strong>ی ماڵپەڕ و پلاتفۆرم کە سەردانکەر دەگۆڕێت بۆ کڕیار.</p>',
                    'ar' => '<p>نعمل مع فريقك <strong>لتصميم وبناء وإطلاق</strong> مواقع ومنصات تحوّل الزوار إلى عملاء.</p>',
                ],
                'body' => [
                    'en' => '<p>Small senior squads, weekly demos, and documentation your internal team can own. We optimize for Core Web Vitals, accessibility, and editor-friendly CMS workflows.</p>',
                    'ckb' => '<p>تیمی بچووکی پسپۆڕ، پیشاندانی هەفتانە و دۆکیومێنت بۆ تیمەکەت. کارایی وێب، دەستڕاگەیشتن و CMSی ئاسان بۆ دەستکاری.</p>',
                    'ar' => '<p>فرق صغيرة خبيرة، عروض أسبوعية، وتوثيق يمكن لفريقك الداخلي امتلاكه. نُحسّن الأداء وإمكانية الوصول وسير عمل CMS سهل.</p>',
                ],
            ],
            'benefits' => [
                'eyebrow' => ['en' => 'Benefits', 'ckb' => 'سوودەکان', 'ar' => 'الفوائد'],
                'title' => ['en' => 'One partner from strategy to launch', 'ckb' => 'یەک هاوبەش لە ستراتیژی تا بڵاوکردنەوە', 'ar' => 'شريك واحد من الاستراتيجية إلى الإطلاق'],
                'lead' => [
                    'en' => '<p>Strategy workshops, UX, visual design, Laravel + Vue engineering, analytics, and post-launch support — aligned under one roadmap.</p>',
                    'ckb' => '<p>وۆرکشۆپی ستراتیژی، UX، دیزاین، Laravel + Vue، شیکاری و پشتگیری دوای بڵاوکردنەوە — لە یەک ڕێڕەو.</p>',
                    'ar' => '<p>ورش استراتيجية، UX، تصميم، Laravel + Vue، تحليلات ودعم ما بعد الإطلاق — في خارطة طريق واحدة.</p>',
                ],
            ],
            'process' => [
                'eyebrow' => ['en' => 'Process', 'ckb' => 'پرۆسە', 'ar' => 'المنهجية'],
                'title' => ['en' => 'How we deliver', 'ckb' => 'چۆن دەگەیەنین', 'ar' => 'كيف نُسلّم'],
                'lead' => [
                    'en' => '<p>Four clear phases — discovery, design, build, and grow — with shared boards, async updates, and stakeholder reviews built in.</p>',
                    'ckb' => '<p>چوار قۆناغی ڕوون — دۆزینەوە، دیزاین، دروستکردن و گەشە — بە بۆردی هاوبەش و پێداچوونەوەی بەردەوام.</p>',
                    'ar' => '<p>أربع مراحل واضحة — اكتشاف، تصميم، بناء ونمو — مع لوحات مشتركة ومراجعات دورية.</p>',
                ],
            ],
            'portfolio' => [
                'eyebrow' => ['en' => 'Portfolio', 'ckb' => 'پۆرتفۆلیۆ', 'ar' => 'معرض الأعمال'],
                'title' => ['en' => 'Selected launches', 'ckb' => 'بڵاوکراوە هەڵبژێردراوەکان', 'ar' => 'إطلاقات مختارة'],
                'lead' => [
                    'en' => '<p>Retail, healthcare, education, and public-sector platforms — shipped with multilingual content and measurable performance gains.</p>',
                    'ckb' => '<p>پلاتفۆرمی بازرگانی، تەندروستی، پەروەردە و کەرتی گشتی — بە ناوەڕۆکی فرە زمان و باشترکردنی کارایی.</p>',
                    'ar' => '<p>منصات تجارة وتعليم وصحة وقطاع عام — مع محتوى متعدد اللغات وتحسينات أداء ملموسة.</p>',
                ],
            ],
            'pricing' => [
                'eyebrow' => ['en' => 'Engagements', 'ckb' => 'هاوبەشییەکان', 'ar' => 'نماذج التعاون'],
                'title' => ['en' => 'Plans that match your stage', 'ckb' => 'پلانەکان گونجاو لەگەڵ قۆناغەکەت', 'ar' => 'خطط تناسب مرحلتك'],
                'lead' => [
                    'en' => '<p>Starter sprints for MVPs, partnership retainers for product teams, and enterprise programs for multi-site rollouts — scoped transparently.</p>',
                    'ckb' => '<p>سپرینتی دەستپێک بۆ MVP، ڕێککەوتنی درێژخایەن بۆ تیمی بەرهەم و بەرنامەی گەورە بۆ چەند ماڵپەڕ — بە قیستی ڕوون.</p>',
                    'ar' => '<p>سبرنتات للبداية، شراكات شهرية للفرق، وبرامج مؤسسية للمواقع المتعددة — بنطاق شفاف.</p>',
                ],
            ],
            'faq' => [
                'eyebrow' => ['en' => 'FAQ', 'ckb' => 'پرسیارە باوەکان', 'ar' => 'الأسئلة الشائعة'],
                'title' => ['en' => 'Questions before we kick off', 'ckb' => 'پرسیار پێش دەستپێکردن', 'ar' => 'أسئلة قبل الانطلاق'],
                'lead' => [
                    'en' => '<p>Timelines, languages, handoff, and support — answered upfront so you can move forward with confidence.</p>',
                    'ckb' => '<p>کات، زمان، گواستنەوە و پشتگیری — وەڵام لە سەرەتادا بۆ بڕۆیت بە دڵنیایی.</p>',
                    'ar' => '<p>الجداول واللغات والتسليم والدعم — إجابات واضحة منذ البداية.</p>',
                ],
            ],
            'reviews' => [
                'eyebrow' => ['en' => 'Client voices', 'ckb' => 'دەنگی کڕیاران', 'ar' => 'آراء العملاء'],
                'title' => ['en' => 'Trusted across sectors', 'ckb' => 'متمانەپێکراو لە چەندین بوار', 'ar' => 'موثوق عبر قطاعات متعددة'],
                'lead' => [
                    'en' => '<p>Product leaders, founders, and marketing directors who needed speed without sacrificing polish.</p>',
                    'ckb' => '<p>سەرکردەی بەرهەم، دامەزرێنەر و بەڕێوەبەری بازاڕیگەری کە خێرایی و کوالیتیان دەوێت.</p>',
                    'ar' => '<p>قادة منتجات ومؤسسون ومديرو تسويق يحتاجون السرعة دون التضحية بالجودة.</p>',
                ],
            ],
            'takeaways' => [
                'eyebrow' => ['en' => 'Why it works', 'ckb' => 'بۆچی سەرکەوتووە', 'ar' => 'لماذا ينجح'],
                'title' => ['en' => 'Clarity at every step', 'ckb' => 'ڕوونی لە هەموو هەنگاوێک', 'ar' => 'وضوح في كل خطوة'],
                'lead' => [
                    'en' => '<p>You get a single roadmap, bilingual-ready content structures, and a team that documents decisions your staff can maintain.</p>',
                    'ckb' => '<p>یەک ڕێڕەو، ناوەڕۆکی ئامادە بۆ فرە زمان و تیمێک کە بڕیارەکان دۆکیومێنت دەکات بۆ تیمەکەت.</p>',
                    'ar' => '<p>خارطة طريق واحدة، هياكل محتوى جاهزة للغات، وفريق يوثّق القرارات لفريقك الداخلي.</p>',
                ],
            ],
            'final_cta' => [
                'title' => ['en' => 'Ready to shape your next launch?', 'ckb' => 'ئامادەیت بۆ بڵاوکردنەوەی داهاتوو؟', 'ar' => 'مستعد لإطلاقك القادم؟'],
                'lead' => [
                    'en' => '<p>Share a short brief — we will reply within one business day with scope options, timeline, and a recommended squad.</p>',
                    'ckb' => '<p>پوختەیەک بنووسە — لە یەک ڕۆژی کاردا وەڵام دەدەینەوە بە قیست و کات و تیمی پێشنیارکراو.</p>',
                    'ar' => '<p>أرسل ملخصاً قصيراً — نرد خلال يوم عمل واحد بالنطاق والجدول والفريق المقترح.</p>',
                ],
            ],
            'team' => [
                'eyebrow' => ['en' => 'Team', 'ckb' => 'تیم', 'ar' => 'الفريق'],
                'title' => ['en' => 'Senior makers, local context', 'ckb' => 'پسپۆڕی ناوخۆیی، تێگەیشتنی ناوچەیی', 'ar' => 'خبراء محليون بسياق إقليمي'],
            ],
        ];
    }

    /**
     * @return array<int, array{key: string, value: string, group: string}>
     */
    public static function seederRows(): array
    {
        $rows = [];
        foreach (self::defaultCatalog() as $section => $fields) {
            foreach ($fields as $field => $byLocale) {
                foreach ($byLocale as $locale => $value) {
                    $rows[] = [
                        'key' => self::settingKey($section, $field, $locale),
                        'value' => $value,
                        'group' => 'home',
                    ];
                }
            }
        }

        return $rows;
    }

    private static function defaultValue(string $section, string $field, string $locale): string
    {
        return self::defaultCatalog()[$section][$field][$locale]
            ?? self::defaultCatalog()[$section][$field]['en']
            ?? '';
    }
}

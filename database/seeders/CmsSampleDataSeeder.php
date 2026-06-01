<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Service;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSampleDataSeeder extends Seeder
{
    public function run(): void
    {
        $locales = array_keys(config('app.locales'));

        DB::transaction(function () use ($locales): void {
            $this->seedAboutPage($locales);
            $this->seedServices($locales);
            $this->seedProjects($locales);
            $this->seedTeam($locales);
            $this->seedShop($locales);
            $this->seedTestimonials($locales);
        });
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedAboutPage(array $locales): void
    {
        $page = Page::query()->updateOrCreate(
            ['slug' => 'about'],
            ['is_active' => true, 'order' => 0]
        );

        $content = [
            'en' => <<<'HTML'
<h2>Who we are</h2>
<p>We are a multidisciplinary studio helping organizations ship memorable digital products—from discovery workshops to launch-day performance tuning.</p>
<h2>How we work</h2>
<ul>
<li>Small senior teams embedded with yours</li>
<li>Design systems that scale across web and product</li>
<li>Transparent milestones and weekly demos</li>
</ul>
<h2>Outcomes</h2>
<p>Clients rely on us for flagship marketing sites, customer portals, and internal tools that stay fast as teams grow.</p>
HTML,
            'ar' => <<<'HTML'
<h2>من نحن</h2>
<p>استوديو متعدد التخصصات يساعد المؤسسات على إطلاق منتجات رقمية مميزة—من ورش الاكتشاف إلى ضبط الأداء يوم الإطلاق.</p>
<h2>أسلوب عملنا</h2>
<ul>
<li>فرق صغيرة من الخبراء تعمل معكم</li>
<li>أنظمة تصميم قابلة للتوسع</li>
<li>معالم واضحة وعروض أسبوعية</li>
</ul>
<h2>النتائج</h2>
<p>نعمل على المواقع التسويقية وبوابات العملاء والأدوات الداخلية.</p>
HTML,
            'ckb' => <<<'HTML'
<h2>ئێمە کێین</h2>
<p>ستۆدیۆیەکی فرەبواری یارمەتیدەرمان بۆ ڕێکخراوەکان بۆ بەرهەمهێنانی بەرهەمی دیجیتاڵ—لە وۆرکشۆپەکانی دۆزینەوە تا باشکردنی کارایی.</p>
<h2>چۆنیەتی کار</h2>
<ul>
<li>تیمە بچووکە پسپۆڕەکان لەگەڵ تۆدا</li>
<li>سیستەمی دیزاین بۆ وێب و بەرهەم</li>
<li>قۆناغی ڕوون و پیشاندان هەفتانە</li>
</ul>
<h2>ئەنجام</h2>
<p>ماڵپەڕی بازرگانی، دەروازەی کڕیار، ئامرازە ناوخۆییەکان.</p>
HTML,
        ];

        foreach ($locales as $locale) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => match ($locale) {
                        'ar' => 'من نحن',
                        'ckb' => 'دەربارەی ئێمە',
                        default => 'About our studio',
                    },
                    'content' => $content[$locale] ?? $content['en'],
                    'meta_title' => match ($locale) {
                        'ar' => 'من نحن',
                        'ckb' => 'دەربارە',
                        default => 'About — Digital studio',
                    },
                    'meta_description' => match ($locale) {
                        'ar' => 'قصة الفريق والنهج والنتائج.',
                        'ckb' => 'چیرۆکی تیم و ڕێگاکەمان.',
                        default => 'Team story, how we work, and outcomes for modern brands.',
                    },
                ]
            );
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedServices(array $locales): void
    {
        $titles = [
            'en' => ['Product strategy', 'UX research & prototyping', 'Brand & design systems', 'Web applications', 'Performance & Core Web Vitals', 'Analytics & experimentation'],
            'ar' => ['استراتيجية المنتج', 'بحث تجربة المستخدم', 'الهوية وأنظمة التصميم', 'تطبيقات الويب', 'الأداء وسرعة التحميل', 'التحليلات والتجارب'],
            'ckb' => ['ستراتیژی بەرهەم', 'توێژینەوەی UX', 'براند و سیستەمی دیزاین', 'ئەپڵیکەیشنی وێب', 'کارایی و خێرایی', 'شیکاری و تاقیکردنەوە'],
        ];

        $descSnippet = static function (string $locale, int $i): string {
            $body = match ($locale) {
                'ar' => '<p>نغطي الاكتشاف، النماذج الأولية، والتسليم مع فريقك.</p><p>أدوات حديثة، توثيق واضح، ودعم ما بعد الإطلاق.</p>',
                'ckb' => '<p>دۆزینەوە، نموونە، و گەیاندن لەگەڵ تیمەکەت.</p><p>ئامرازە نوێیەکان و پشتگیری دوای بڵاوکردنەوە.</p>',
                default => '<p>Discovery, prototyping, and delivery alongside your team—with clear milestones and shared ownership.</p><p>We document decisions, instrument releases, and stay through stabilization.</p>',
            };

            return '<h3>What you get</h3>'.$body.'<ul><li>Workshops &amp; alignment</li><li>Design QA with engineering</li><li>Launch checklist &amp; handover</li></ul>';
        };

        for ($i = 1; $i <= 6; $i++) {
            $service = Service::query()->create([
                'is_active' => true,
                'order' => $i,
            ]);

            foreach ($locales as $locale) {
                $service->translations()->create([
                    'locale' => $locale,
                    'slug' => 'service-'.$i.'-'.$locale,
                    'title' => $titles[$locale][$i - 1] ?? $titles['en'][$i - 1],
                    'description' => $descSnippet($locale, $i),
                ]);
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedProjects(array $locales): void
    {
        $categories = ['Web', 'Mobile', 'Branding', 'Product', 'Marketing', 'Internal tools'];

        for ($i = 1; $i <= 15; $i++) {
            $project = Project::query()->create([
                'client' => ['Northwind Labs', 'Harbor Health', 'Atlas Finance', 'Riverstone Retail', 'Cedar Education', 'Summit Logistics'][$i % 6],
                'year' => 2018 + ($i % 8),
                'category' => $categories[$i % 6],
                'gallery' => [],
                'is_active' => true,
                'order' => $i,
            ]);

            foreach ($locales as $locale) {
                $title = match ($locale) {
                    'ar' => 'دراسة حالة '.$i.' — منصة رقمية',
                    'ckb' => 'کەیس '.$i.' — پلاتفۆرم',
                    default => 'Case study '.$i.' — digital platform',
                };

                $html = match ($locale) {
                    'ar' => '<p><strong>التحدي:</strong> توحيد المحتوى عبر عدة فرق.</p><p><strong>الحل:</strong> نظام تصميم، بوابة محتوى، وتكاملات API.</p><blockquote>انخفاض زمن التحميل بنسبة ملحوظة بعد الإطلاق.</blockquote>',
                    'ckb' => '<p><strong>ئاستەنگ:</strong> یەکخستنی ناوەڕۆک.</p><p><strong>چارەسەر:</strong> سیستەمی دیزاین و API.</p><blockquote>خێرایی بارکردن باشتر بوو دوای بڵاوکردنەوە.</blockquote>',
                    default => '<p><strong>Challenge:</strong> unify storytelling across regions while keeping editors productive.</p><p><strong>Approach:</strong> design system, modular CMS patterns, and API integrations with your existing stack.</p><blockquote>Core Web Vitals improved measurably within two sprints of launch.</blockquote>',
                };

                $project->translations()->create([
                    'locale' => $locale,
                    'slug' => 'project-'.$i.'-'.$locale,
                    'title' => $title,
                    'description' => $html,
                    'tags' => ['Laravel', 'Vue', 'Inertia', 'Tailwind', 'Design'],
                ]);
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedTeam(array $locales): void
    {
        $roles = [
            'en' => ['Creative Director', 'Lead Engineer', 'Product Designer', 'PM & Delivery', 'Brand Strategist', 'QA Lead', 'Front-end Developer', 'Content Strategist'],
            'ar' => ['مدير إبداعي', 'مهندس رئيسي', 'مصمم منتج', 'إدارة وتسليم', 'استراتيجي علامة', 'مسؤول جودة', 'مطور واجهات', 'استراتيجي محتوى'],
            'ckb' => ['دەرهێنەر', 'ئەندازیاری سەرەکی', 'دیزاینەری بەرهەم', 'بەڕێوەبردن', 'ستراتیژی براند', 'سەرپەرشتی QA', 'فرۆنتێند', 'ستراتیژی ناوەڕۆک'],
        ];

        for ($i = 1; $i <= 8; $i++) {
            $member = TeamMember::query()->create([
                'role_key' => $i <= 3 ? 'lead' : 'member',
                'linkedin' => 'https://www.linkedin.com/company/example',
                'is_active' => true,
                'order' => $i,
            ]);

            foreach ($locales as $locale) {
                $bio = match ($locale) {
                    'ar' => '<p>خبرة في المنتجات الرقمية والتعاون مع العملاء.</p><p>يركز على الجودة والوضوح.</p>',
                    'ckb' => '<p>ئەزموون لە بەرهەمی دیجیتاڵ و هاوکاری کڕیار.</p><p>جەخت لەسەر کوالیتی.</p>',
                    default => '<p>Hands-on experience shipping web products with cross-functional teams.</p><p>Care deeply about accessible UI, clear copy, and maintainable code.</p>',
                };

                $member->translations()->create([
                    'locale' => $locale,
                    'name' => match ($locale) {
                        'ar' => 'عضو الفريق '.$i,
                        'ckb' => 'ئەندامی تیم '.$i,
                        default => 'Team member '.$i,
                    },
                    'position' => $roles[$locale][$i - 1] ?? $roles['en'][$i - 1],
                    'bio' => $bio,
                ]);
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedShop(array $locales): void
    {
        for ($c = 1; $c <= 4; $c++) {
            $pc = ProductCategory::query()->create([
                'is_active' => true,
                'order' => $c,
            ]);

            foreach ($locales as $locale) {
                $desc = match ($locale) {
                    'ar' => '<p>مجموعة منتجات مختارة لهذا الخط.</p><ul><li>ضمان الجودة</li><li>شحن سريع</li></ul>',
                    'ckb' => '<p>کۆمەڵێک بەرهەم بۆ ئەم هێڵە.</p><ul><li>کوالیتی</li><li>گەیاندن</li></ul>',
                    default => '<p>Curated picks in this line—documentation, warranties, and onboarding templates included.</p><ul><li>Quality-checked SKUs</li><li>Volume pricing on request</li></ul>',
                };

                $pc->translations()->create([
                    'locale' => $locale,
                    'slug' => 'shop-cat-'.$c.'-'.$locale,
                    'title' => match ($locale) {
                        'ar' => 'تصنيف '.$c,
                        'ckb' => 'هێڵی بەرهەم '.$c,
                        default => match ($c) {
                            1 => 'Starter kits',
                            2 => 'Pro bundles',
                            3 => 'Enterprise add-ons',
                            default => 'Merchandise',
                        },
                    },
                    'description' => $desc,
                ]);
            }
        }

        $shopCategories = ProductCategory::query()->orderBy('order')->get();

        foreach ($shopCategories as $ci => $pc) {
            for ($p = 1; $p <= 4; $p++) {
                $product = Product::query()->create([
                    'product_category_id' => $pc->id,
                    'sku' => 'SKU-'.$pc->id.'-'.$p,
                    'price' => 49.0 + ($ci * 10) + ($p * 5),
                    'is_active' => true,
                    'order' => $ci * 4 + $p,
                ]);

                foreach ($locales as $locale) {
                    $product->translations()->create([
                        'locale' => $locale,
                        'slug' => 'shop-cat-'.$pc->id.'-'.$p.'-'.$locale,
                        'title' => match ($locale) {
                            'ar' => 'منتج '.$pc->id.'-'.$p,
                            'ckb' => 'بەرهەم '.$pc->id.'-'.$p,
                            default => 'Product '.$pc->id.'-'.$p.' — '.match ($p) {
                                1 => 'Essentials pack',
                                2 => 'Team pack',
                                3 => 'Scale pack',
                                default => 'Add-on module',
                            },
                        },
                        'description' => match ($locale) {
                            'ar' => '<p>وصف تفصيلي للمنتج مع <strong>مزايا رئيسية</strong>.</p><p>مناسب للفرق النامية.</p>',
                            'ckb' => '<p>وردەکاری بەرهەم و <strong>تایبەتمەندی</strong>.</p><p>گونجاو بۆ تیم.</p>',
                            default => '<p>Full <strong>feature overview</strong> with implementation notes and suggested rollout plan.</p><p>Includes email support during onboarding.</p>',
                        },
                    ]);
                }
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedTestimonials(array $locales): void
    {
        $companies = ['Northwind Labs', 'Harbor Health', 'Atlas Finance', 'Riverstone Retail', 'Cedar Education', 'Summit Logistics', 'Bluepeak Media', 'Ironwood Manufacturing'];

        for ($i = 1; $i <= 8; $i++) {
            $row = Testimonial::query()->create([
                'rating' => $i % 2 === 0 ? 5 : 4,
                'is_active' => true,
                'order' => $i,
            ]);

            foreach ($locales as $locale) {
                $content = match ($locale) {
                    'ar' => '<p>«فريق محترف، تواصل ممتاز، وتسليم في الوقت.»</p><p>نوصي بهم بشدة لمشاريع المنتجات.</p>',
                    'ckb' => '<p>«تیمێکی پیشەگەر و گەیاندنی کاتخۆ.»</p><p>پێشنیار دەکەین بۆ بەرهەم.</p>',
                    default => '<p>«Clear communication, pragmatic scope, and a launch we could defend to our board.»</p><p>They became an extension of our product trio within the first sprint.</p>',
                };

                $row->translations()->create([
                    'locale' => $locale,
                    'author_name' => match ($locale) {
                        'ar' => 'عميل '.$i,
                        'ckb' => 'کڕیار '.$i,
                        default => 'Alex Morgan '.$i,
                    },
                    'company' => $companies[$i - 1],
                    'content' => $content,
                ]);
            }
        }
    }
}

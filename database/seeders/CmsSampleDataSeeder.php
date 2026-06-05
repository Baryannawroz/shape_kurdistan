<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Project;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\TeamMember;
use App\Models\Testimonial;
use Database\Seeders\Support\SeederMedia;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsSampleDataSeeder extends Seeder
{
    /**
     * @var array{hero: string, work: string, team: string, code: string}
     */
    private array $media = [];

    public function run(): void
    {
        $this->media = SeederMedia::paths();
        $locales = array_keys(config('app.locales'));

        DB::transaction(function () use ($locales): void {
            $this->seedAboutPage($locales);
            $this->seedListPages($locales);
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
<h2>Rooted in Kurdistan, built for modern teams</h2>
<p>Shape Kurdistan is a digital studio in Erbil helping organizations launch websites, customer portals, and product platforms with bilingual workflows and measurable performance.</p>
<h2>What we bring</h2>
<ul>
<li><strong>Strategy first</strong> — workshops that align stakeholders before pixels or code</li>
<li><strong>Design systems</strong> — reusable components across web and product surfaces</li>
<li><strong>Engineering craft</strong> — Laravel, Vue, and Inertia stacks your team can maintain</li>
<li><strong>Launch support</strong> — analytics, QA, and stabilization after go-live</li>
</ul>
<h2>Who we work with</h2>
<p>Retail groups, healthcare providers, universities, NGOs, and growing product companies across Iraq and the Kurdistan Region.</p>
HTML,
            'ar' => <<<'HTML'
<h2>متجذرون في كردستان، نبني للفرق الحديثة</h2>
<p>شيب كردستان استوديو رقمي في أربيل يساعد المؤسسات على إطلاق مواقع وبوابات ومنصات بمحتوى ثنائي اللغة وأداء قابل للقياس.</p>
<h2>ما نقدمه</h2>
<ul>
<li><strong>الاستراتيجية أولاً</strong> — ورش عمل تُوحّد أصحاب المصلحة</li>
<li><strong>أنظمة التصميم</strong> — مكونات قابلة لإعادة الاستخدام</li>
<li><strong>هندسة موثوقة</strong> — Laravel وVue وInertia</li>
<li><strong>دعم الإطلاق</strong> — تحليلات واختبارات بعد التشغيل</li>
</ul>
<h2>من نخدم</h2>
<p>تجزئة، رعاية صحية، جامعات، منظمات غير ربحية وشركات ناشئة في العراق وإقليم كردستان.</p>
HTML,
            'ckb' => <<<'HTML'
<h2>لە کوردستانەوە، بۆ تیمە هەنووکەییەکان</h2>
<p>شێپ کوردستان ستۆدیۆیەکی دیجیتاڵە لە هەولێر کە یارمەتی ڕێکخراوەکان دەدات ماڵپەڕ، دەروازە و پلاتفۆرم بڵاوبکەنەوە بە ناوەڕۆکی دوو زمانە و کارایی پێوانەکراو.</p>
<h2>چی پێشکەش دەکەین</h2>
<ul>
<li><strong>ستراتیژی لە پێشەوە</strong> — وۆرکشۆپ بۆ هاوسۆزی لایەنەکان</li>
<li><strong>سیستەمی دیزاین</strong> — پێکهاتەی دووبارە بەکارهێنراو</li>
<li><strong>ئەندازیاری جێگیر</strong> — Laravel، Vue و Inertia</li>
<li><strong>پشتگیری بڵاوکردنەوە</strong> — شیکاری و تاقیکردنەوە دوای بڵاوکردنەوە</li>
</ul>
<h2>لەگەڵ کێ کار دەکەین</h2>
<p>کۆمپانیا بازرگانی، تەندروستی، زانکۆ، ڕێکخراوە ناحکومییەکان و کۆمپانیا گەشەسەندووەکان لە عێراق و هەرێمی کوردستان.</p>
HTML,
        ];

        foreach ($locales as $locale) {
            $page->translations()->updateOrCreate(
                ['locale' => $locale],
                [
                    'title' => match ($locale) {
                        'ar' => 'عن شيب كردستان',
                        'ckb' => 'دەربارەی شێپ کوردستان',
                        default => 'About Shape Kurdistan',
                    },
                    'content' => $content[$locale] ?? $content['en'],
                    'meta_title' => match ($locale) {
                        'ar' => 'من نحن — شيب كردستان',
                        'ckb' => 'دەربارە — شێپ کوردستان',
                        default => 'About — Shape Kurdistan',
                    },
                    'meta_description' => match ($locale) {
                        'ar' => 'استوديو رقمي في أربيل للاستراتيجية والتصميم والهندسة.',
                        'ckb' => 'ستۆدیۆی دیجیتاڵ لە هەولێر بۆ ستراتیژی و دیزاین و ئەندازیاری.',
                        default => 'Erbil-based digital studio for strategy, design, and engineering.',
                    },
                ]
            );
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedListPages(array $locales): void
    {
        $pages = [
            'services' => [
                'en' => ['title' => 'Services', 'content' => 'End-to-end capabilities — from discovery workshops and UX prototypes to Laravel applications, performance tuning, and post-launch analytics.'],
                'ar' => ['title' => 'الخدمات', 'content' => 'قدرات متكاملة — من ورش الاكتشاف ونماذج UX إلى تطبيقات Laravel وتحسين الأداء والتحليلات.'],
                'ckb' => ['title' => 'خزمەتگوزاریەکان', 'content' => 'توانای تەواو — لە وۆرکشۆپی دۆزینەوە و UX تا ئەپڵیکەیشنی Laravel و باشترکردنی کارایی.'],
            ],
            'products' => [
                'en' => ['title' => 'Products & kits', 'content' => 'Curated starter kits, retainers, and add-on modules you can scope quickly — each with documentation and onboarding support.'],
                'ar' => ['title' => 'المنتجات والحزم', 'content' => 'حزم بداية واشتراكات ووحدات إضافية مع توثيق ودعم للتشغيل.'],
                'ckb' => ['title' => 'بەرهەم و کێتەکان', 'content' => 'کێتی دەستپێک و ڕێککەوتن و مۆدیولی زیادە بە دۆکیومێنت و پشتگیری دەستپێکردن.'],
            ],
            'portfolio' => [
                'en' => ['title' => 'Portfolio', 'content' => 'A snapshot of launches across retail, healthcare, education, and public programs — filter by discipline or open a case study.'],
                'ar' => ['title' => 'معرض الأعمال', 'content' => 'لمحة عن إطلاقات في التجزئة والصحة والتعليم — صفّ حسب التخصص أو افتح دراسة حالة.'],
                'ckb' => ['title' => 'پۆرتفۆلیۆ', 'content' => 'کورتەی بڵاوکراوەکان لە بازرگانی و تەندروستی و پەروەردە — فلتەر بەپێی بوار یان کەیس بکەرەوە.'],
            ],
        ];

        foreach ($pages as $slug => $translations) {
            $page = Page::query()->updateOrCreate(
                ['slug' => $slug],
                ['is_active' => true, 'order' => 0]
            );

            foreach ($locales as $locale) {
                $row = $translations[$locale] ?? $translations['en'];
                $page->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'title' => $row['title'],
                        'content' => '<p>'.$row['content'].'</p>',
                    ]
                );
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedServices(array $locales): void
    {
        $definitions = [
            [
                'slug' => 'product-strategy',
                'image' => 'work',
                'titles' => ['en' => 'Product strategy', 'ar' => 'استراتيجية المنتج', 'ckb' => 'ستراتیژی بەرهەم'],
            ],
            [
                'slug' => 'ux-research',
                'image' => 'hero',
                'titles' => ['en' => 'UX research & prototyping', 'ar' => 'بحث UX والنماذج الأولية', 'ckb' => 'توێژینەوەی UX و نموونە'],
            ],
            [
                'slug' => 'brand-design-systems',
                'image' => 'work',
                'titles' => ['en' => 'Brand & design systems', 'ar' => 'الهوية وأنظمة التصميم', 'ckb' => 'براند و سیستەمی دیزاین'],
            ],
            [
                'slug' => 'web-applications',
                'image' => 'code',
                'titles' => ['en' => 'Web applications', 'ar' => 'تطبيقات الويب', 'ckb' => 'ئەپڵیکەیشنی وێب'],
            ],
            [
                'slug' => 'performance-seo',
                'image' => 'code',
                'titles' => ['en' => 'Performance & SEO', 'ar' => 'الأداء وتحسين البحث', 'ckb' => 'کارایی و SEO'],
            ],
            [
                'slug' => 'analytics-growth',
                'image' => 'work',
                'titles' => ['en' => 'Analytics & growth', 'ar' => 'التحليلات والنمو', 'ckb' => 'شیکاری و گەشە'],
            ],
        ];

        foreach ($definitions as $index => $definition) {
            $service = $this->findServiceByEnSlug($definition['slug']) ?? new Service;
            $service->fill([
                'is_active' => true,
                'order' => $index + 1,
                'image' => $this->media[$definition['image']],
            ]);
            $service->save();

            foreach ($locales as $locale) {
                $service->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $definition['slug'].($locale === 'en' ? '' : '-'.$locale),
                        'title' => $definition['titles'][$locale] ?? $definition['titles']['en'],
                        'description' => $this->serviceDescription($locale, $definition['titles']['en']),
                    ]
                );
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedProjects(array $locales): void
    {
        $definitions = [
            ['slug' => 'erbil-commerce-portal', 'client' => 'Erbil Commerce Group', 'year' => 2025, 'category' => 'Web', 'image' => 'work'],
            ['slug' => 'kurdistan-tourism-experience', 'client' => 'Visit Kurdistan', 'year' => 2024, 'category' => 'Marketing', 'image' => 'hero'],
            ['slug' => 'harbor-health-patient-app', 'client' => 'Harbor Health', 'year' => 2024, 'category' => 'Product', 'image' => 'code'],
            ['slug' => 'cedar-university-learning-hub', 'client' => 'Cedar University', 'year' => 2023, 'category' => 'Education', 'image' => 'work'],
            ['slug' => 'riverstone-retail-refresh', 'client' => 'Riverstone Retail', 'year' => 2023, 'category' => 'Web', 'image' => 'hero'],
            ['slug' => 'summit-logistics-tracker', 'client' => 'Summit Logistics', 'year' => 2022, 'category' => 'Internal tools', 'image' => 'code'],
            ['slug' => 'heritage-archive-platform', 'client' => 'Kurdish Heritage Trust', 'year' => 2022, 'category' => 'Branding', 'image' => 'work'],
            ['slug' => 'atlas-fintech-onboarding', 'client' => 'Atlas Finance', 'year' => 2021, 'category' => 'Product', 'image' => 'code'],
            ['slug' => 'ngo-impact-dashboard', 'client' => 'Impact NGO Coalition', 'year' => 2021, 'category' => 'Marketing', 'image' => 'hero'],
        ];

        foreach ($definitions as $index => $definition) {
            $project = $this->findProjectByEnSlug($definition['slug']) ?? new Project;
            $project->fill([
                'client' => $definition['client'],
                'year' => $definition['year'],
                'category' => $definition['category'],
                'gallery' => [$this->media[$definition['image']]],
                'image' => $this->media[$definition['image']],
                'is_active' => true,
                'order' => $index + 1,
            ]);
            $project->save();

            foreach ($locales as $locale) {
                $project->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $definition['slug'].($locale === 'en' ? '' : '-'.$locale),
                        'title' => $this->projectTitle($locale, $definition),
                        'description' => $this->projectDescription($locale, $definition),
                        'tags' => ['Laravel', 'Vue', 'Inertia', 'Tailwind', 'Multilingual'],
                    ]
                );
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedTeam(array $locales): void
    {
        $members = [
            ['name' => 'Baryan Rasul', 'role' => ['en' => 'Creative Director', 'ar' => 'المدير الإبداعي', 'ckb' => 'دەرهێنەری داهێنەر']],
            ['name' => 'Hozan Aziz', 'role' => ['en' => 'Lead Engineer', 'ar' => 'المهندس الرئيسي', 'ckb' => 'ئەندازیاری سەرەکی']],
            ['name' => 'Shilan Omar', 'role' => ['en' => 'Product Designer', 'ar' => 'مصممة المنتج', 'ckb' => 'دیزاینەری بەرهەم']],
            ['name' => 'Karwan Salih', 'role' => ['en' => 'Delivery Lead', 'ar' => 'قائد التسليم', 'ckb' => 'سەرکردەی گەیاندن']],
            ['name' => 'Naza Farid', 'role' => ['en' => 'Brand Strategist', 'ar' => 'استراتيجية العلامة', 'ckb' => 'ستراتیژی براند']],
            ['name' => 'Rebin Ali', 'role' => ['en' => 'Front-end Developer', 'ar' => 'مطور واجهات', 'ckb' => 'گەشەپێدەری فرۆنتێند']],
        ];

        foreach ($members as $index => $member) {
            $row = TeamMember::query()->updateOrCreate(
                ['order' => $index + 1],
                [
                    'role_key' => $index < 2 ? 'lead' : 'member',
                    'photo' => $this->media['team'],
                    'linkedin' => 'https://www.linkedin.com/company/shapekurdistan',
                    'is_active' => true,
                ]
            );

            foreach ($locales as $locale) {
                $row->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'name' => $member['name'],
                        'position' => $member['role'][$locale] ?? $member['role']['en'],
                        'bio' => $this->teamBio($locale, $member['name']),
                    ]
                );
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedShop(array $locales): void
    {
        $categories = [
            ['slug' => 'launch-kits', 'image' => 'hero', 'titles' => ['en' => 'Launch kits', 'ar' => 'حزم الإطلاق', 'ckb' => 'کێتی بڵاوکردنەوە']],
            ['slug' => 'retainers', 'image' => 'work', 'titles' => ['en' => 'Retainers', 'ar' => 'اشتراكات شهرية', 'ckb' => 'ڕێککەوتنی مانگانە']],
            ['slug' => 'enterprise', 'image' => 'code', 'titles' => ['en' => 'Enterprise programs', 'ar' => 'برامج المؤسسات', 'ckb' => 'بەرنامەی گەورە']],
            ['slug' => 'add-ons', 'image' => 'work', 'titles' => ['en' => 'Add-on modules', 'ar' => 'وحدات إضافية', 'ckb' => 'مۆدیولی زیادە']],
        ];

        foreach ($categories as $index => $category) {
            $pc = $this->findCategoryByEnSlug($category['slug']) ?? new ProductCategory;
            $pc->fill([
                'is_active' => true,
                'order' => $index + 1,
                'image' => $this->media[$category['image']],
            ]);
            $pc->save();

            foreach ($locales as $locale) {
                $pc->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $category['slug'].($locale === 'en' ? '' : '-'.$locale),
                        'title' => $category['titles'][$locale] ?? $category['titles']['en'],
                        'description' => $this->categoryDescription($locale),
                    ]
                );
            }

            $this->seedProductsForCategory($pc, $locales, $index);
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedProductsForCategory(ProductCategory $category, array $locales, int $categoryIndex): void
    {
        $productNames = [
            ['en' => 'Discovery sprint', 'ar' => 'سبرنت الاكتشاف', 'ckb' => 'سپرینتی دۆزینەوە'],
            ['en' => 'Design system starter', 'ar' => 'بداية نظام التصميم', 'ckb' => 'دەستپێکی سیستەمی دیزاین'],
            ['en' => 'Launch readiness pack', 'ar' => 'حزمة جاهزية الإطلاق', 'ckb' => 'پاکێجی ئامادەیی بڵاوکردنەوە'],
        ];

        foreach ($productNames as $productIndex => $names) {
            $sku = 'SK-'.$category->id.'-'.($productIndex + 1);
            $product = Product::query()->updateOrCreate(
                ['sku' => $sku],
                [
                    'product_category_id' => $category->id,
                    'image' => $this->media[$productIndex % 2 === 0 ? 'work' : 'hero'],
                    'price' => 890 + ($categoryIndex * 250) + ($productIndex * 120),
                    'is_active' => true,
                    'order' => ($categoryIndex * 3) + $productIndex + 1,
                ]
            );

            foreach ($locales as $locale) {
                $product->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'slug' => $sku.($locale === 'en' ? '' : '-'.$locale),
                        'title' => $names[$locale] ?? $names['en'],
                        'description' => $this->productDescription($locale),
                    ]
                );
            }
        }
    }

    /**
     * @param  array<int, string>  $locales
     */
    private function seedTestimonials(array $locales): void
    {
        $rows = [
            ['author' => 'Sara Mahmoud', 'company' => 'Erbil Commerce Group'],
            ['author' => 'Dr. Alan Kareem', 'company' => 'Harbor Health'],
            ['author' => 'Lana Yasin', 'company' => 'Cedar University'],
            ['author' => 'Omar Hadi', 'company' => 'Riverstone Retail'],
            ['author' => 'Hemin Rashid', 'company' => 'Summit Logistics'],
            ['author' => 'Dilan Azad', 'company' => 'Visit Kurdistan'],
        ];

        foreach ($rows as $index => $row) {
            $testimonial = Testimonial::query()->updateOrCreate(
                ['order' => $index + 1],
                [
                    'rating' => 5,
                    'avatar' => $this->media['team'],
                    'is_active' => true,
                ]
            );

            foreach ($locales as $locale) {
                $testimonial->translations()->updateOrCreate(
                    ['locale' => $locale],
                    [
                        'author_name' => $row['author'],
                        'company' => $row['company'],
                        'content' => $this->testimonialContent($locale, $row['company']),
                    ]
                );
            }
        }
    }

    private function findServiceByEnSlug(string $slug): ?Service
    {
        $translation = ServiceTranslation::query()
            ->where('locale', 'en')
            ->where('slug', $slug)
            ->first();

        return $translation?->service;
    }

    private function findProjectByEnSlug(string $slug): ?Project
    {
        return Project::query()
            ->whereHas('translations', fn ($query) => $query->where('locale', 'en')->where('slug', $slug))
            ->first();
    }

    private function findCategoryByEnSlug(string $slug): ?ProductCategory
    {
        return ProductCategory::query()
            ->whereHas('translations', fn ($query) => $query->where('locale', 'en')->where('slug', $slug))
            ->first();
    }

    private function serviceDescription(string $locale, string $topic): string
    {
        return match ($locale) {
            'ar' => "<p>برنامج عملي حول <strong>{$topic}</strong> يشمل ورش العمل، النماذج، والتسليم مع فريقك.</p><ul><li>خطة واضحة بالمعالم</li><li>توثيق ثنائي اللغة</li><li>دعم ما بعد الإطلاق</li></ul>",
            'ckb' => "<p>بەرنامەیەکی کرداری دەربارەی <strong>{$topic}</strong> لەگەڵ وۆرکشۆپ، نموونە و گەیاندن لەگەڵ تیمەکەت.</p><ul><li>پلانی قۆناغی ڕوون</li><li>دۆکیومێنتی دوو زمانە</li><li>پشتگیری دوای بڵاوکردنەوە</li></ul>",
            default => "<p>Hands-on <strong>{$topic}</strong> engagements with workshops, prototypes, and delivery alongside your team.</p><ul><li>Milestone roadmap</li><li>Bilingual-ready documentation</li><li>Post-launch stabilization</li></ul>",
        };
    }

    /**
     * @param  array{slug: string, client: string, year: int, category: string, image: string}  $definition
     */
    private function projectTitle(string $locale, array $definition): string
    {
        $base = str_replace('-', ' ', $definition['slug']);

        return match ($locale) {
            'ar' => 'دراسة حالة — '.$definition['client'],
            'ckb' => 'کەیسی '.$definition['client'],
            default => ucwords($base),
        };
    }

    /**
     * @param  array{slug: string, client: string, year: int, category: string, image: string}  $definition
     */
    private function projectDescription(string $locale, array $definition): string
    {
        return match ($locale) {
            'ar' => "<p><strong>العميل:</strong> {$definition['client']} ({$definition['year']})</p><p><strong>التحدي:</strong> توحيد المحتوى متعدد اللغات مع أداء عالٍ.</p><p><strong>النتيجة:</strong> منصة أسرع، تحرير أسهل، وتحويل أعلى.</p>",
            'ckb' => "<p><strong>کڕیار:</strong> {$definition['client']} ({$definition['year']})</p><p><strong>ئاستەنگ:</strong> ناوەڕۆکی فرە زمان و کارایی بەرز.</p><p><strong>ئەنجام:</strong> پلاتفۆرمێکی خێراتر و گۆڕینی زیاتر.</p>",
            default => "<p><strong>Client:</strong> {$definition['client']} ({$definition['year']})</p><p><strong>Challenge:</strong> unify multilingual content without sacrificing performance.</p><p><strong>Outcome:</strong> faster pages, happier editors, and higher conversion on key journeys.</p>",
        };
    }

    private function teamBio(string $locale, string $name): string
    {
        return match ($locale) {
            'ar' => "<p>{$name} يقود مسارات التسليم مع تركيز على الجودة والوضوح للعملاء في كردستان والمنطقة.</p>",
            'ckb' => "<p>{$name} ڕێنیگری گەیاندن دەکات بە جەختکردن لەسەر کوالیتی و ڕوونی بۆ کڕیاران لە کوردستان.</p>",
            default => "<p>{$name} ships digital work with a focus on craft, clarity, and long-term maintainability for teams across Kurdistan.</p>",
        };
    }

    private function categoryDescription(string $locale): string
    {
        return match ($locale) {
            'ar' => '<p>حزم مرنة مع نطاق شفاف ودعم للتشغيل.</p>',
            'ckb' => '<p>کۆمەڵەی گونجاو بە قیستی ڕوون و پشتگیری دەستپێکردن.</p>',
            default => '<p>Flexible bundles with transparent scope, documentation, and onboarding support.</p>',
        };
    }

    private function productDescription(string $locale): string
    {
        return match ($locale) {
            'ar' => '<p>يشمل جلسات عمل، قائمة تسليم، وقالب توثيق جاهز لفريقك.</p>',
            'ckb' => '<p>وۆرکشۆپ، لیستی گەیاندن و دۆکیومێنتی ئامادە بۆ تیمەکەت.</p>',
            default => '<p>Includes working sessions, delivery checklist, and documentation templates your team can reuse.</p>',
        };
    }

    private function testimonialContent(string $locale, string $company): string
    {
        return match ($locale) {
            'ar' => "<p>«تعاملنا مع Shape Kurdistan على منصة {$company} — تواصل ممتاز وتسليم في الوقت.»</p><p>أصبحوا شريكاً نثق به بعد أول سبرنت.</p>",
            'ckb' => "<p>«لەگەڵ شێپ کوردستان کارمان کرد بۆ {$company} — پەیوەندی باش و گەیاندنی کاتخۆ.»</p><p>دوای یەکەم سپرینت هاوبەشی متمانەپێکراو بوون.</p>",
            default => "<p>«Shape Kurdistan helped {$company} ship on time with bilingual content and a performance budget we could defend.»</p><p>They felt like an extension of our product team after the first sprint.</p>",
        };
    }
}

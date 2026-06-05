<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(SiteContentSeeder::class);

        $sections = [
            [
                'slug' => 'technology',
                'name' => ['en' => 'Technology', 'ckb' => 'Teqnolojî', 'ar' => 'تكنولوجيا'],
                'subtitle' => ['en' => 'Innovation and Future', 'ckb' => 'Nûbûn û Pêşeroj', 'ar' => 'الابتكار والمستقبل'],
                'color_theme' => '#3B82F6',
            ],
            [
                'slug' => 'health',
                'name' => ['en' => 'Health', 'ckb' => 'Tenduristî', 'ar' => 'صحة'],
                'subtitle' => ['en' => 'Caring for Life', 'ckb' => 'Lênêrîna Jiyanê', 'ar' => 'رعاية الحياة'],
                'color_theme' => '#10B981',
            ],
            [
                'slug' => 'education',
                'name' => ['en' => 'Education', 'ckb' => 'Perwerde', 'ar' => 'تعليم'],
                'subtitle' => ['en' => 'Building Knowledge', 'ckb' => 'Avakirina Zanînê', 'ar' => 'بناء المعرفة'],
                'color_theme' => '#F59E0B',
            ],
            [
                'slug' => 'environment',
                'name' => ['en' => 'Environment', 'ckb' => 'Jîngeh', 'ar' => 'بيئة'],
                'subtitle' => ['en' => 'Protecting Nature', 'ckb' => 'Parastina Xwezayê', 'ar' => 'حماية الطبيعة'],
                'color_theme' => '#15803D',
            ],
            [
                'slug' => 'culture',
                'name' => ['en' => 'Culture', 'ckb' => 'Çand', 'ar' => 'ثقافة'],
                'subtitle' => ['en' => 'Our Heritage', 'ckb' => 'Mîrasa Me', 'ar' => 'تراثنا'],
                'color_theme' => '#8B5CF6',
            ],
        ];

        foreach ($sections as $data) {
            Section::query()->create(array_merge($data, [
                'description' => ['en' => 'Description for '.$data['name']['en'], 'ckb' => 'Danasîn ji bo '.$data['name']['ckb'], 'ar' => 'وصف لـ '.$data['name']['ar']],
                'about_content' => ['en' => '<p>About content...</p>', 'ckb' => '<p>Naveroka derbarê...</p>', 'ar' => '<p>محتوى عن...</p>'],
                'contact_email' => 'contact@'.$data['slug'].'.com',
                'contact_phone' => '+1234567890',
                'contact_address' => ['en' => 'Address Line 1', 'ckb' => 'Navnîşan 1', 'ar' => 'العنوان 1'],
            ]));
        }
    }
}

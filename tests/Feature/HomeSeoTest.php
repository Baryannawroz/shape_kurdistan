<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use App\Support\HomeSections;
use App\Support\PageSeo;
use Database\Seeders\CmsSettingsSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class HomeSeoTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_exposes_seo_and_sections_for_guests(): void
    {
        $this->seed(CmsSettingsSeeder::class);

        $this->get('/en')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('sections.why_choose.title')
                ->has('sections.vision.title')
                ->has('sections.mission.title')
                ->where('seo.title', fn (string $title) => $title !== '')
                ->where('seo.description', fn (string $description) => $description !== '')
            );
    }

    public function test_page_seo_uses_saved_settings(): void
    {
        Setting::query()->create([
            'key' => 'seo.page.about.title_en',
            'value' => 'Custom About Title',
            'group' => 'seo',
        ]);
        Setting::query()->create([
            'key' => 'seo.page.about.description_en',
            'value' => 'Custom about description for Google.',
            'group' => 'seo',
        ]);

        $seo = PageSeo::resolve('about', 'Fallback', 'Fallback description');

        $this->assertSame('Custom About Title', $seo['title']);
        $this->assertSame('Custom about description for Google.', $seo['description']);
    }

    public function test_home_sections_returns_localized_content(): void
    {
        Setting::query()->create([
            'key' => 'home.vision_title_en',
            'value' => 'Our Vision',
            'group' => 'home',
        ]);
        Setting::query()->create([
            'key' => 'home.mission_title_en',
            'value' => 'Our Mission',
            'group' => 'home',
        ]);

        $sections = HomeSections::forLocale('en');

        $this->assertSame('Our Vision', $sections['vision']['title']);
        $this->assertSame('Our Mission', $sections['mission']['title']);
    }

    public function test_admin_receives_seo_settings_on_contact_page(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);
        $this->seed(CmsSettingsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get('/en/contact')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->has('seoSettings')
                ->where('seoSettings', function (mixed $settings): bool {
                    $values = is_array($settings) ? $settings : $settings->all();

                    return ($values['seo.page.contact.title_en'] ?? '') !== '';
                })
            );
    }
}

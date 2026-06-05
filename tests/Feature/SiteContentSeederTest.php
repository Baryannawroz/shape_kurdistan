<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Service;
use App\Models\Setting;
use App\Models\TeamMember;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SiteContentSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_site_content_seeder_populates_branded_settings_and_media(): void
    {
        Storage::fake('public');

        $assetDir = database_path('seeders/assets');
        foreach (['hero-studio.jpg', 'showcase-work.jpg', 'team-portrait.jpg', 'project-code.jpg'] as $file) {
            Storage::disk('public')->put('seed/'.$file, file_get_contents($assetDir.'/'.$file));
            Storage::disk('public')->put('seed/thumbs/'.$file, file_get_contents($assetDir.'/'.$file));
        }

        $this->seed(SiteContentSeeder::class);

        $this->assertSame('Shape Kurdistan', Setting::query()->where('key', 'general.site_name_en')->value('value'));
        $this->assertNotNull(Setting::query()->where('key', 'appearance.hero_image')->value('value'));
        $this->assertSame('Why teams choose Shape Kurdistan', Setting::query()->where('key', 'home.why_choose_title_en')->value('value'));

        $this->assertGreaterThanOrEqual(6, Service::query()->count());
        $this->assertGreaterThanOrEqual(9, Project::query()->whereNotNull('image')->count());
        $this->assertGreaterThanOrEqual(6, TeamMember::query()->whereNotNull('photo')->count());
    }
}

<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Support\RichContent;
use Database\Seeders\CmsSampleDataSeeder;
use Database\Seeders\CmsSettingsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HostedVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_rich_content_expands_hosted_video_shortcode(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('videos/demo.mp4', 'video-bytes');

        $html = RichContent::expand('<p>Intro</p>[[video:videos/demo.mp4]]<p>Outro</p>');

        $this->assertStringContainsString('hosted-video', $html);
        $this->assertStringContainsString('/storage/videos/demo.mp4', $html);
        $this->assertStringNotContainsString('youtube', $html);
    }

    public function test_rich_content_expands_youtube_shortcode(): void
    {
        $html = RichContent::expand('<p>Intro</p>[[video:Ty4fon7-Uhs]]');

        $this->assertStringContainsString('youtube-video', $html);
        $this->assertStringContainsString('data-youtube-id="Ty4fon7-Uhs"', $html);
        $this->assertStringNotContainsString('youtube.com', $html);
    }

    public function test_about_page_renders_hosted_video_placeholder(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('videos/demo.mp4', 'video-bytes');

        $this->seed([CmsSettingsSeeder::class, CmsSampleDataSeeder::class]);

        $page = Page::query()->where('slug', 'about')->firstOrFail();
        $page->translations()->updateOrCreate(
            ['locale' => 'en'],
            ['title' => 'About', 'content' => '<p>Watch</p>[[video:videos/demo.mp4]]']
        );

        $this->get('/en/about')
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Front/About')
                ->where('content', fn (string $content) => str_contains($content, 'data-video-src') && str_contains($content, 'videos/demo.mp4'))
            );
    }
}

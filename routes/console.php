<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Post;
use App\Models\Section;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('sitemap:generate', function () {
    $this->info('Generating sitemap...');
    
    $sitemap = Sitemap::create();
    
    // Add base URL
    $sitemap->add(Url::create('/'));
    
    // Add locales
    $locales = array_keys(config('app.locales', ['en', 'ckb', 'ar']));
    
    foreach ($locales as $locale) {
        $this->info("Processing locale: $locale");
        
        $sitemap->add(Url::create("/{$locale}"));
        $sitemap->add(Url::create("/{$locale}/blog"));
        
        Section::where('is_active', true)->chunk(100, function ($sections) use ($sitemap, $locale) {
            foreach ($sections as $section) {
                $sitemap->add(Url::create("/{$locale}/section/{$section->slug}"));
                $sitemap->add(Url::create("/{$locale}/section/{$section->slug}/about"));
                $sitemap->add(Url::create("/{$locale}/section/{$section->slug}/contact"));
            }
        });
        
        Post::where('status', 'published')->chunk(100, function ($posts) use ($sitemap, $locale) {
            foreach ($posts as $post) {
                $sitemap->add(Url::create("/{$locale}/blog/{$post->slug}"));
            }
        });
    }
    
    $path = public_path('sitemap.xml');
    $sitemap->writeToFile($path);
    
    $this->info("Sitemap generated successfully at $path");
})->purpose('Generate the sitemap.xml');

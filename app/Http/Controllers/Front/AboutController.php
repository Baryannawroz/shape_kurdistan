<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Front\Concerns\LoadsPageIntro;
use App\Models\Page;
use App\Models\TeamMember;
use App\Support\AdminEditLinks;
use App\Support\PageSeo;
use App\Support\RichContent;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    use LoadsPageIntro;

    public function index(): Response
    {
        $page = Page::query()->where('slug', 'about')->where('is_active', true)->with('translations')->first();
        $translation = $page?->getTranslation();
        $pageIntro = $this->loadPageIntro('about', [
            'eyebrow' => 'About',
            'title' => __('About'),
            'lead' => 'Our story, craft, and how we partner with teams like yours.',
        ]);

        $team = TeamMember::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (TeamMember $m) => [
                'id' => $m->id,
                'name' => $m->getTranslation()?->name,
                'position' => $m->getTranslation()?->position,
                'bio' => $m->getTranslation()?->bio,
                'photo' => $m->photo ? Storage::url($m->photo) : null,
                'linkedin' => $m->linkedin,
            ]);

        return Inertia::render('Front/About', [
            'adminEdits' => AdminEditLinks::build(request()->user(), AdminEditLinks::forAbout($page?->id)),
            'seo' => PageSeo::resolve('about', $translation?->title ?? __('About'), strip_tags($translation?->content ?? '')),
            'seoSettings' => AdminEditLinks::canManage(request()->user()) ? PageSeo::settingsPayload('about') : null,
            'pageContent' => $pageIntro['pageContent'],
            'localeMeta' => $pageIntro['localeMeta'],
            'intro' => $pageIntro['intro'],
            'title' => $translation?->title ?? __('About'),
            'content' => RichContent::expand($translation?->content),
            'team' => $team,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\TeamMember;
use App\Support\RichContent;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AboutController extends Controller
{
    public function index(): Response
    {
        $page = Page::query()->where('slug', 'about')->where('is_active', true)->with('translations')->first();
        $translation = $page?->getTranslation();

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
            'title' => $translation?->title ?? __('About'),
            'content' => RichContent::expand($translation?->content),
            'team' => $team,
        ]);
    }
}

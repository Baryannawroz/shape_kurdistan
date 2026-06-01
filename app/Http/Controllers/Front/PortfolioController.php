<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectTranslation;
use App\Support\RichContent;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class PortfolioController extends Controller
{
    public function index(): Response
    {
        $projects = Project::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (Project $p) => [
                'id' => $p->id,
                'title' => $p->getTranslation()?->title,
                'slug' => $p->getTranslation()?->slug,
                'category' => $p->category,
                'client' => $p->client,
                'year' => $p->year,
                'image' => $p->image ? Storage::url($p->image) : null,
                'gallery' => collect($p->gallery ?? [])->map(fn ($path) => Storage::url($path))->all(),
            ]);

        $categories = Project::query()
            ->where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values()
            ->all();

        return Inertia::render('Front/Portfolio', [
            'projects' => $projects,
            'categories' => $categories,
        ]);
    }

    public function show(string $locale, string $slug): Response
    {
        $translation = ProjectTranslation::query()
            ->where('locale', app()->getLocale())
            ->where('slug', $slug)
            ->with(['project.translations'])
            ->firstOrFail();

        $project = $translation->project;
        $t = $project->getTranslation();

        $related = Project::query()
            ->where('is_active', true)
            ->where('id', '!=', $project->id)
            ->where('category', $project->category)
            ->with('translations')
            ->limit(4)
            ->get()
            ->map(fn (Project $p) => [
                'id' => $p->id,
                'title' => $p->getTranslation()?->title,
                'slug' => $p->getTranslation()?->slug,
                'image' => $p->image ? Storage::url($p->image) : null,
            ]);

        return Inertia::render('Front/ProjectShow', [
            'project' => [
                'id' => $project->id,
                'title' => $t?->title,
                'description' => RichContent::expand($t?->description),
                'slug' => $t?->slug,
                'tags' => $t?->tags ?? [],
                'client' => $project->client,
                'year' => $project->year,
                'category' => $project->category,
                'image' => $project->image ? Storage::url($project->image) : null,
                'gallery' => collect($project->gallery ?? [])->map(fn ($path) => Storage::url($path))->all(),
            ],
            'related' => $related,
        ]);
    }
}

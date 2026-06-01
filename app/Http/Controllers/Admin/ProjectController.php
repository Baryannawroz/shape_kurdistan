<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProjectFormRequest;
use App\Models\Project;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProjectController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $projects = Project::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (Project $p) => [
                'id' => $p->id,
                'order' => $p->order,
                'is_active' => $p->is_active,
                'category' => $p->category,
                'title' => $p->getTranslation(config('app.fallback_locale'))?->title,
            ]);

        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Projects/Edit', [
            'project' => null,
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(ProjectFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $gallery = [];

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery', []) as $file) {
                    if ($file) {
                        $gallery[] = $this->images->storeWithThumbnail($file, 'projects')['path'];
                    }
                }
            }

            $data = [
                'client' => $request->input('client'),
                'year' => $request->input('year'),
                'category' => $request->input('category'),
                'gallery' => $gallery,
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'projects')['path'];
            }

            $project = Project::query()->create($data);

            foreach ($request->validated()['translations'] as $row) {
                $project->translations()->create([
                    'locale' => $row['locale'],
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'description' => $row['description'] ?? null,
                    'tags' => $row['tags'] ?? [],
                ]);
            }
        });

        return redirect()->route('admin.cms.projects.index')->with('success', __('Saved.'));
    }

    public function edit(Project $project): Response
    {
        $project->load('translations');

        return Inertia::render('Admin/Projects/Edit', [
            'project' => [
                'id' => $project->id,
                'order' => $project->order,
                'is_active' => $project->is_active,
                'client' => $project->client,
                'year' => $project->year,
                'category' => $project->category,
                'image' => $project->image,
                'gallery' => $project->gallery ?? [],
                'translations' => $project->translations->keyBy('locale'),
            ],
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(ProjectFormRequest $request, Project $project): RedirectResponse
    {
        DB::transaction(function () use ($request, $project): void {
            $gallery = $project->gallery ?? [];

            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery', []) as $file) {
                    if ($file) {
                        $gallery[] = $this->images->storeWithThumbnail($file, 'projects')['path'];
                    }
                }
            }

            $data = [
                'client' => $request->input('client'),
                'year' => $request->input('year'),
                'category' => $request->input('category'),
                'gallery' => $gallery,
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'projects')['path'];
            }

            $project->update($data);

            foreach ($request->validated()['translations'] as $row) {
                $project->translations()->updateOrCreate(
                    ['locale' => $row['locale']],
                    [
                        'slug' => $row['slug'],
                        'title' => $row['title'],
                        'description' => $row['description'] ?? null,
                        'tags' => $row['tags'] ?? [],
                    ]
                );
            }
        });

        return redirect()->back()->with('success', __('Saved.'));
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('admin.cms.projects.index')->with('success', __('Deleted.'));
    }

    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:projects,id'],
        ]);

        foreach ($order['ids'] as $index => $id) {
            Project::query()->whereKey($id)->update(['order' => $index]);
        }

        return redirect()->back()->with('success', __('Order updated.'));
    }
}

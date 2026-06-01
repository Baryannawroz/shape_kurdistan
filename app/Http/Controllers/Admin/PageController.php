<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageFormRequest;
use App\Http\Requests\Admin\UploadVideoRequest;
use App\Models\Page;
use App\Services\VideoUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function index(): Response
    {
        $pages = Page::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (Page $page) => [
                'id' => $page->id,
                'slug' => $page->slug,
                'is_active' => $page->is_active,
                'order' => $page->order,
                'title' => $page->getTranslation(config('app.fallback_locale'))?->title,
            ]);

        return Inertia::render('Admin/Pages/Index', [
            'pages' => $pages,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Pages/Edit', [
            'page' => null,
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(PageFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $page = Page::query()->create([
                'slug' => $request->validated('slug'),
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ]);

            foreach ($request->validated()['translations'] as $row) {
                $page->translations()->create([
                    'locale' => $row['locale'],
                    'title' => $row['title'],
                    'content' => $row['content'] ?? null,
                    'meta_title' => $row['meta_title'] ?? null,
                    'meta_description' => $row['meta_description'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.cms.pages.index')->with('success', __('Saved.'));
    }

    public function edit(Page $page): Response
    {
        $page->load('translations');

        return Inertia::render('Admin/Pages/Edit', [
            'page' => [
                'id' => $page->id,
                'slug' => $page->slug,
                'is_active' => $page->is_active,
                'order' => $page->order,
                'translations' => $page->translations->keyBy('locale'),
            ],
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(PageFormRequest $request, Page $page): RedirectResponse
    {
        DB::transaction(function () use ($request, $page): void {
            $page->update([
                'slug' => $request->validated('slug'),
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ]);

            foreach ($request->validated()['translations'] as $row) {
                $page->translations()->updateOrCreate(
                    ['locale' => $row['locale']],
                    [
                        'title' => $row['title'],
                        'content' => $row['content'] ?? null,
                        'meta_title' => $row['meta_title'] ?? null,
                        'meta_description' => $row['meta_description'] ?? null,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', __('Saved.'));
    }

    public function destroy(Page $page): RedirectResponse
    {
        $page->delete();

        return redirect()->route('admin.cms.pages.index')->with('success', __('Deleted.'));
    }

    public function uploadVideo(UploadVideoRequest $request, VideoUploadService $videoUploadService): JsonResponse
    {
        $path = $videoUploadService->store($request->file('video'));

        return response()->json([
            'path' => $path,
            'url' => $videoUploadService->publicUrl($path),
            'shortcode' => '[[video:'.$path.']]',
        ]);
    }
}

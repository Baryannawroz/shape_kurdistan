<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ServiceFormRequest;
use App\Models\Service;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ServiceController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $services = Service::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (Service $s) => [
                'id' => $s->id,
                'order' => $s->order,
                'is_active' => $s->is_active,
                'title' => $s->getTranslation(config('app.fallback_locale'))?->title,
            ]);

        return Inertia::render('Admin/Services/Index', [
            'services' => $services,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Services/Edit', [
            'service' => null,
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(ServiceFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = [
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('icon')) {
                $data['icon'] = $this->images->storeWithThumbnail($request->file('icon'), 'services')['path'];
            }

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'services')['path'];
            }

            $service = Service::query()->create($data);

            foreach ($request->validated()['translations'] as $row) {
                $service->translations()->create([
                    'locale' => $row['locale'],
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'description' => $row['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.cms.services.index')->with('success', __('Saved.'));
    }

    public function edit(Service $service): Response
    {
        $service->load('translations');

        return Inertia::render('Admin/Services/Edit', [
            'service' => [
                'id' => $service->id,
                'order' => $service->order,
                'is_active' => $service->is_active,
                'icon' => $service->icon,
                'image' => $service->image,
                'translations' => $service->translations->keyBy('locale'),
            ],
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(ServiceFormRequest $request, Service $service): RedirectResponse
    {
        DB::transaction(function () use ($request, $service): void {
            $data = [
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('icon')) {
                $data['icon'] = $this->images->storeWithThumbnail($request->file('icon'), 'services')['path'];
            }

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'services')['path'];
            }

            $service->update($data);

            foreach ($request->validated()['translations'] as $row) {
                $service->translations()->updateOrCreate(
                    ['locale' => $row['locale']],
                    [
                        'slug' => $row['slug'],
                        'title' => $row['title'],
                        'description' => $row['description'] ?? null,
                    ]
                );
            }
        });

        return redirect()->back()->with('success', __('Saved.'));
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();

        return redirect()->route('admin.cms.services.index')->with('success', __('Deleted.'));
    }

    public function reorder(\Illuminate\Http\Request $request): RedirectResponse
    {
        $order = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:services,id'],
        ]);

        foreach ($order['ids'] as $index => $id) {
            Service::query()->whereKey($id)->update(['order' => $index]);
        }

        return redirect()->back()->with('success', __('Order updated.'));
    }
}

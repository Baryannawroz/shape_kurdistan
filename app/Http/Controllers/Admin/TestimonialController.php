<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TestimonialFormRequest;
use App\Models\Testimonial;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TestimonialController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $rows = Testimonial::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (Testimonial $t) => [
                'id' => $t->id,
                'order' => $t->order,
                'is_active' => $t->is_active,
                'rating' => $t->rating,
                'author' => $t->getTranslation(config('app.fallback_locale'))?->author_name,
            ]);

        return Inertia::render('Admin/Testimonials/Index', [
            'testimonials' => $rows,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Testimonials/Edit', [
            'testimonial' => null,
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(TestimonialFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = [
                'rating' => (int) $request->input('rating', 5),
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->images->storeWithThumbnail($request->file('avatar'), 'testimonials')['path'];
            }

            $testimonial = Testimonial::query()->create($data);

            foreach ($request->validated()['translations'] as $row) {
                $testimonial->translations()->create([
                    'locale' => $row['locale'],
                    'author_name' => $row['author_name'],
                    'company' => $row['company'] ?? null,
                    'content' => $row['content'],
                ]);
            }
        });

        return redirect()->route('admin.cms.testimonials.index')->with('success', __('Saved.'));
    }

    public function edit(Testimonial $testimonial): Response
    {
        $testimonial->load('translations');

        return Inertia::render('Admin/Testimonials/Edit', [
            'testimonial' => [
                'id' => $testimonial->id,
                'order' => $testimonial->order,
                'is_active' => $testimonial->is_active,
                'rating' => $testimonial->rating,
                'avatar' => $testimonial->avatar,
                'translations' => $testimonial->translations->keyBy('locale'),
            ],
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(TestimonialFormRequest $request, Testimonial $testimonial): RedirectResponse
    {
        DB::transaction(function () use ($request, $testimonial): void {
            $data = [
                'rating' => (int) $request->input('rating', 5),
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('avatar')) {
                $data['avatar'] = $this->images->storeWithThumbnail($request->file('avatar'), 'testimonials')['path'];
            }

            $testimonial->update($data);

            foreach ($request->validated()['translations'] as $row) {
                $testimonial->translations()->updateOrCreate(
                    ['locale' => $row['locale']],
                    [
                        'author_name' => $row['author_name'],
                        'company' => $row['company'] ?? null,
                        'content' => $row['content'],
                    ]
                );
            }
        });

        return redirect()->back()->with('success', __('Saved.'));
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        $testimonial->delete();

        return redirect()->route('admin.cms.testimonials.index')->with('success', __('Deleted.'));
    }

    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:testimonials,id'],
        ]);

        foreach ($order['ids'] as $index => $id) {
            Testimonial::query()->whereKey($id)->update(['order' => $index]);
        }

        return redirect()->back()->with('success', __('Order updated.'));
    }
}

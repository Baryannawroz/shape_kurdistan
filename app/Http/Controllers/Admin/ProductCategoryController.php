<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCategoryFormRequest;
use App\Models\ProductCategory;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductCategoryController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $categories = ProductCategory::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (ProductCategory $c) => [
                'id' => $c->id,
                'order' => $c->order,
                'is_active' => $c->is_active,
                'title' => $c->getTranslation(config('app.fallback_locale'))?->title,
            ]);

        return Inertia::render('Admin/ProductCategories/Index', [
            'categories' => $categories,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/ProductCategories/Edit', [
            'category' => null,
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(ProductCategoryFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $data = [
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'product-categories')['path'];
            }

            $category = ProductCategory::query()->create($data);

            foreach ($request->validated()['translations'] as $row) {
                $category->translations()->create([
                    'locale' => $row['locale'],
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'description' => $row['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.cms.product-categories.index')->with('success', __('Saved.'));
    }

    public function edit(ProductCategory $product_category): Response
    {
        $product_category->load('translations');

        return Inertia::render('Admin/ProductCategories/Edit', [
            'category' => [
                'id' => $product_category->id,
                'order' => $product_category->order,
                'is_active' => $product_category->is_active,
                'image' => $product_category->image,
                'translations' => $product_category->translations->keyBy('locale'),
            ],
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(ProductCategoryFormRequest $request, ProductCategory $product_category): RedirectResponse
    {
        DB::transaction(function () use ($request, $product_category): void {
            $data = [
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'product-categories')['path'];
            }

            $product_category->update($data);

            foreach ($request->validated()['translations'] as $row) {
                $product_category->translations()->updateOrCreate(
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

    public function destroy(ProductCategory $product_category): RedirectResponse
    {
        $product_category->delete();

        return redirect()->route('admin.cms.product-categories.index')->with('success', __('Deleted.'));
    }

    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:product_categories,id'],
        ]);

        foreach ($order['ids'] as $index => $id) {
            ProductCategory::query()->whereKey($id)->update(['order' => $index]);
        }

        return redirect()->back()->with('success', __('Order updated.'));
    }
}

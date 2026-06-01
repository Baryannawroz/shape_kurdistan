<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductFormRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\ImageUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function __construct(
        private ImageUploadService $images
    ) {}

    public function index(): Response
    {
        $products = Product::query()
            ->with(['translations', 'productCategory.translations'])
            ->orderBy('order')
            ->get()
            ->map(function (Product $p) {
                $t = $p->getTranslation(config('app.fallback_locale'));
                $cat = $p->productCategory?->getTranslation(config('app.fallback_locale'));

                return [
                    'id' => $p->id,
                    'order' => $p->order,
                    'is_active' => $p->is_active,
                    'title' => $t?->title,
                    'category_title' => $cat?->title,
                ];
            });

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => null,
            'categories' => $this->categoryOptions(),
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function store(ProductFormRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request): void {
            $validated = $request->validated();
            $data = [
                'product_category_id' => (int) $validated['product_category_id'],
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
                'sku' => $validated['sku'] ?? null,
                'price' => isset($validated['price']) && $validated['price'] !== '' && $validated['price'] !== null
                    ? $validated['price']
                    : null,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'products')['path'];
            }

            $product = Product::query()->create($data);

            foreach ($validated['translations'] as $row) {
                $product->translations()->create([
                    'locale' => $row['locale'],
                    'slug' => $row['slug'],
                    'title' => $row['title'],
                    'description' => $row['description'] ?? null,
                ]);
            }
        });

        return redirect()->route('admin.cms.products.index')->with('success', __('Saved.'));
    }

    public function edit(Product $product): Response
    {
        $product->load('translations');

        return Inertia::render('Admin/Products/Edit', [
            'product' => [
                'id' => $product->id,
                'product_category_id' => $product->product_category_id,
                'order' => $product->order,
                'is_active' => $product->is_active,
                'sku' => $product->sku,
                'price' => $product->price,
                'image' => $product->image,
                'translations' => $product->translations->keyBy('locale'),
            ],
            'categories' => $this->categoryOptions(),
            'locales' => array_keys(config('app.locales')),
        ]);
    }

    public function update(ProductFormRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product): void {
            $validated = $request->validated();
            $data = [
                'product_category_id' => (int) $validated['product_category_id'],
                'is_active' => $request->boolean('is_active', true),
                'order' => (int) $request->input('order', 0),
                'sku' => $validated['sku'] ?? null,
                'price' => isset($validated['price']) && $validated['price'] !== '' && $validated['price'] !== null
                    ? $validated['price']
                    : null,
            ];

            if ($request->hasFile('image')) {
                $data['image'] = $this->images->storeWithThumbnail($request->file('image'), 'products')['path'];
            }

            $product->update($data);

            foreach ($validated['translations'] as $row) {
                $product->translations()->updateOrCreate(
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

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('admin.cms.products.index')->with('success', __('Deleted.'));
    }

    public function reorder(Request $request): RedirectResponse
    {
        $order = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:products,id'],
        ]);

        foreach ($order['ids'] as $index => $id) {
            Product::query()->whereKey($id)->update(['order' => $index]);
        }

        return redirect()->back()->with('success', __('Order updated.'));
    }

    /**
     * @return array<int, array{id: int, label: string}>
     */
    private function categoryOptions(): array
    {
        $fallback = config('app.fallback_locale');

        return ProductCategory::query()
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (ProductCategory $c) => [
                'id' => $c->id,
                'label' => $c->getTranslation($fallback)?->title ?? (string) $c->id,
            ])
            ->all();
    }
}

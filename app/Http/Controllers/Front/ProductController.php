<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductTranslation;
use App\Support\RichContent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $locale = app()->getLocale();

        $categories = ProductCategory::query()
            ->where('is_active', true)
            ->with('translations')
            ->orderBy('order')
            ->get()
            ->map(fn (ProductCategory $c) => $this->mapCategory($c, $locale));

        $categorySlug = $request->query('category');

        $productsQuery = Product::query()
            ->where('is_active', true)
            ->with(['translations', 'productCategory.translations'])
            ->orderBy('order');

        if ($categorySlug) {
            $categoryId = ProductCategoryTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $categorySlug)
                ->value('product_category_id');

            if ($categoryId) {
                $productsQuery->where('product_category_id', $categoryId);
            }
        }

        $products = $productsQuery->get()->map(fn (Product $p) => $this->mapProductCard($p, $locale));

        return Inertia::render('Front/Products', [
            'categories' => $categories,
            'products' => $products,
            'activeCategorySlug' => $categorySlug,
        ]);
    }

    public function show(string $locale, string $slug): Response
    {
        $translation = $this->resolveProductTranslationForSlug($slug, $locale);

        $product = Product::query()
            ->whereKey($translation->product_id)
            ->where('is_active', true)
            ->with(['translations', 'productCategory.translations'])
            ->firstOrFail();

        $t = $product->getTranslation($locale);

        $cat = $product->productCategory?->getTranslation($locale);
        $catFallback = $product->productCategory?->getTranslation();

        return Inertia::render('Front/ProductShow', [
            'product' => [
                'title' => $t?->title,
                'description' => RichContent::expand($t?->description),
                'sku' => $product->sku,
                'price' => $product->price,
                'image' => $product->image ? Storage::url($product->image) : null,
                'category' => [
                    'title' => $cat?->title ?? $catFallback?->title,
                    'slug' => $cat?->slug ?? $catFallback?->slug,
                ],
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function mapCategory(ProductCategory $category, string $locale): array
    {
        $t = $category->getTranslation($locale);

        return [
            'id' => $category->id,
            'title' => $t?->title,
            'slug' => $t?->slug,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function mapProductCard(Product $product, string $locale): array
    {
        $t = $product->getTranslation($locale);
        $cat = $product->productCategory?->getTranslation($locale);

        return [
            'id' => $product->id,
            'title' => $t?->title,
            'slug' => $t?->slug,
            'description' => $t?->description,
            'price' => $product->price,
            'image' => $product->image ? Storage::url($product->image) : null,
            'category_title' => $cat?->title,
            'category_slug' => $cat?->slug,
        ];
    }

    /**
     * Matches list cards: {@see HasTranslations::getTranslation()} can surface the fallback locale slug
     * while the URL still uses a non-fallback locale prefix (e.g. /ar/products/{en-slug}).
     */
    private function resolveProductTranslationForSlug(string $slug, string $locale): ProductTranslation
    {
        $fallback = config('app.fallback_locale');

        $forActiveProduct = function () use ($slug): Builder {
            return ProductTranslation::query()
                ->where('slug', $slug)
                ->whereHas('product', fn ($query) => $query->where('is_active', true));
        };

        $translation = $forActiveProduct()->where('locale', $locale)->first();

        if ($translation) {
            return $translation;
        }

        if ($locale !== $fallback) {
            $translation = $forActiveProduct()->where('locale', $fallback)->first();

            if ($translation) {
                return $translation;
            }
        }

        return $forActiveProduct()->firstOrFail();
    }
}

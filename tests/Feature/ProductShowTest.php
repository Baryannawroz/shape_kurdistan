<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_show_resolves_fallback_locale_slug_when_current_locale_translation_missing(): void
    {
        $category = ProductCategory::factory()->create();

        $product = Product::factory()->create([
            'product_category_id' => $category->id,
            'is_active' => true,
        ]);

        ProductTranslation::query()->create([
            'product_id' => $product->id,
            'locale' => 'en',
            'slug' => 'en-only-widget',
            'title' => 'Widget EN',
            'description' => 'Desc',
        ]);

        $this->get('/en/products/en-only-widget')->assertOk();
        $this->get('/ar/products/en-only-widget')->assertOk();
    }

    public function test_product_show_returns_not_found_for_unknown_slug(): void
    {
        $response = $this->get('/en/products/does-not-exist-xyz');

        $response->assertNotFound();
    }
}

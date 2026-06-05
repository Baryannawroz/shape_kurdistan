<?php

namespace Tests\Feature;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\Service;
use App\Models\ServiceTranslation;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia;
use Tests\TestCase;

class AdminPreviewBarTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_do_not_receive_admin_edit_links(): void
    {
        $this->get('/en/contact')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', false)
                ->has('adminEdits', 0)
                ->where('contactSettings', null)
            );
    }

    public function test_admin_receives_inline_about_page_payload(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $page = Page::query()->create([
            'slug' => 'about',
            'is_active' => true,
            'order' => 0,
        ]);
        PageTranslation::query()->create([
            'page_id' => $page->id,
            'locale' => 'en',
            'title' => 'About us',
            'content' => '<p>Our story</p>',
        ]);

        $this->actingAs($user)
            ->get('/en/about')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', true)
                ->has('pageContent')
                ->where('pageContent.slug', 'about')
                ->has('localeMeta')
                ->has('seoSettings')
                ->has('seo.title')
            );
    }

    public function test_admin_receives_home_settings_on_home_page(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get('/en')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', true)
                ->has('homeSettings')
                ->has('localeMeta')
                ->has('sections.why_choose')
                ->has('sections.vision')
                ->has('sections.mission')
                ->has('seo.title')
                ->has('seo.description')
            );
    }

    public function test_admin_receives_contextual_edit_links_on_contact_page(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get('/en/contact')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', true)
                ->has('adminEdits', 2)
                ->has('contactSettings')
                ->has('localeMeta')
            );
    }

    public function test_admin_receives_item_edit_link_on_service_show_page(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('editor');

        $service = Service::query()->create([
            'is_active' => true,
            'order' => 0,
        ]);
        ServiceTranslation::query()->create([
            'service_id' => $service->id,
            'locale' => 'en',
            'slug' => 'consulting',
            'title' => 'Consulting',
            'description' => 'Consulting service',
        ]);

        $this->actingAs($user)
            ->get('/en/services/consulting')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', true)
                ->has('adminEdits', 2)
                ->where('adminEdits.0.label', 'Edit this service')
            );
    }

    public function test_admin_receives_edit_links_on_cms_dashboard(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('admin');

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', true)
                ->has('adminEdits')
                ->where('adminEdits.0.label', 'View site')
            );
    }

    public function test_user_without_cms_role_does_not_receive_admin_edit_links_on_dashboard(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', false)
                ->has('adminEdits', 0)
            );
    }

    public function test_admin_receives_item_edit_link_on_product_show_page(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $product = Product::factory()->create(['is_active' => true]);
        ProductTranslation::query()->create([
            'product_id' => $product->id,
            'locale' => 'en',
            'slug' => 'widget',
            'title' => 'Widget',
            'description' => 'Widget description',
        ]);

        $this->actingAs($user)
            ->get('/en/products/widget')
            ->assertOk()
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->where('canManageSite', true)
                ->where('product.id', $product->id)
                ->where('adminEdits.0.label', 'Edit this product')
            );
    }
}

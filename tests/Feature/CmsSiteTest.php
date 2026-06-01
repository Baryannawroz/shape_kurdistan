<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CmsSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_localized_home_loads(): void
    {
        $this->get('/en')->assertOk();
    }

    public function test_localized_contact_page_loads(): void
    {
        $this->get('/en/contact')->assertOk();
    }

    public function test_localized_products_page_loads(): void
    {
        $this->get('/en/products')->assertOk();
    }

    public function test_contact_submission_persists_message(): void
    {
        $this->withoutMiddleware(ValidateCsrfToken::class);

        $this->post('/en/contact', [
            'name' => 'Casey',
            'email' => 'casey@example.com',
            'phone' => '',
            'subject' => 'Hello',
            'message' => 'Test body content.',
        ])->assertRedirect();

        $this->assertDatabaseHas('contact_messages', [
            'email' => 'casey@example.com',
            'name' => 'Casey',
        ]);
    }

    public function test_admin_cms_pages_redirect_guests(): void
    {
        $this->get(route('admin.cms.pages.index'))->assertRedirect(route('login'));
    }

    public function test_admin_cms_pages_visible_to_super_admin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)->get(route('admin.cms.pages.index'))->assertOk();
    }

    public function test_admin_contact_settings_redirect_guests(): void
    {
        $this->get(route('admin.cms.contact-settings.edit'))->assertRedirect(route('login'));
    }

    public function test_admin_contact_settings_visible_to_super_admin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)->get(route('admin.cms.contact-settings.edit'))->assertOk();
    }

    public function test_admin_product_categories_visible_to_super_admin(): void
    {
        $this->seed(RolesAndPermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('super-admin');

        $this->actingAs($user)->get(route('admin.cms.product-categories.index'))->assertOk();
    }
}

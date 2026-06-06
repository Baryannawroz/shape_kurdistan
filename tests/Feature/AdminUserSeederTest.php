<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\AdminUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserSeederTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_seeder_creates_verified_super_admin_accounts(): void
    {
        $this->seed(AdminUserSeeder::class);

        $baryan = User::query()->where('email', 'baryan@gmail.com')->first();
        $admin = User::query()->where('email', 'admin@site.com')->first();

        $this->assertNotNull($baryan);
        $this->assertNotNull($admin);
        $this->assertTrue($baryan->hasVerifiedEmail());
        $this->assertTrue($admin->hasVerifiedEmail());
        $this->assertTrue($baryan->hasRole('super-admin'));
        $this->assertTrue($admin->hasRole('super-admin'));
        $this->assertTrue(Hash::check('havegood', $baryan->password));
        $this->assertTrue(Hash::check('password', $admin->password));
    }

    public function test_seeded_baryan_can_authenticate_and_reach_admin_dashboard(): void
    {
        $this->seed(AdminUserSeeder::class);

        $this->assertTrue(Auth::attempt([
            'email' => 'baryan@gmail.com',
            'password' => 'havegood',
        ]));

        $baryan = User::query()->where('email', 'baryan@gmail.com')->firstOrFail();

        $this->actingAs($baryan)
            ->get(route('admin.dashboard'))
            ->assertOk();
    }

    public function test_admin_dashboard_shell_uses_light_background_before_vue_mounts(): void
    {
        $this->seed(AdminUserSeeder::class);

        $baryan = User::query()->where('email', 'baryan@gmail.com')->firstOrFail();

        $this->actingAs($baryan)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('color-scheme" content="light"', false)
            ->assertSee('bg-slate-100', false)
            ->assertSee('id="app-loading-fallback"', false);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissionNames = [
            'view dashboard',
            'manage sections',
            'manage posts',
            'manage settings',
            'manage users',
        ];

        foreach ($permissionNames as $name) {
            Permission::query()->firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $editor = Role::query()->firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $editor->syncPermissions(['view dashboard', 'manage posts']);

        $admin = Role::query()->firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::query()->pluck('name')->all());

        $super = Role::query()->firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $super->syncPermissions(Permission::query()->pluck('name')->all());

        User::query()->where('email', 'admin@example.com')->first()?->assignRole('super-admin');
        User::query()->where('email', 'baryan@gmail.com')->first()?->assignRole('super-admin');
        User::query()->where('email', 'admin@site.com')->first()?->assignRole('super-admin');
    }
}

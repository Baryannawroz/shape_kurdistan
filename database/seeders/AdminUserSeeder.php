<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::query()->updateOrCreate(
            ['email' => 'admin@site.com'],
            [
                'name' => 'Site Admin',
                'password' => Hash::make('password'),
            ]
        );

        if (method_exists($user, 'assignRole')) {
            $user->syncRoles(['super-admin']);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RolesAndPermissionsSeeder::class);

        $accounts = [
            [
                'email' => 'admin@site.com',
                'name' => 'Site Admin',
                'password' => 'password',
            ],
            [
                'email' => 'baryan@gmail.com',
                'name' => 'Baryan',
                'password' => 'havegood',
            ],
        ];

        foreach ($accounts as $account) {
            $user = User::query()->updateOrCreate(
                ['email' => $account['email']],
                [
                    'name' => $account['name'],
                    'password' => $account['password'],
                    'email_verified_at' => now(),
                ]
            );

            if (Role::query()->where('name', 'super-admin')->exists()) {
                $user->syncRoles(['super-admin']);
            }
        }
    }
}

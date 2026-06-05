<?php

namespace Database\Seeders;

use Database\Seeders\Support\SeederMedia;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        SeederMedia::paths();

        $this->call([
            CmsSettingsSeeder::class,
            CmsSampleDataSeeder::class,
        ]);
    }
}

<?php

namespace App\Console\Commands;

use Database\Seeders\AdminUserSeeder;
use Database\Seeders\SiteContentSeeder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schema;

class SiteInstallCommand extends Command
{
    protected $signature = 'site:install
                            {--fresh : Drop all tables and run a clean install (use when migrations are broken)}
                            {--skip-seed : Run migrations only, without seeders}';

    protected $description = 'Run migrations and seed the site (admin users + demo content)';

    public function handle(): int
    {
        if ($this->option('fresh')) {
            if (! $this->confirm('This will DELETE all database tables. Continue?', ! $this->input->isInteractive())) {
                $this->warn('Cancelled.');

                return self::FAILURE;
            }

            $this->warn('Dropping all tables and re-running migrations...');
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->line(Artisan::output());
        } else {
            if (! $this->runMigrations()) {
                return self::FAILURE;
            }
        }

        if ($this->option('skip-seed')) {
            $this->info('Migrations complete. Seeding skipped.');

            return self::SUCCESS;
        }

        $this->info('Seeding admin users...');
        Artisan::call('db:seed', ['--class' => AdminUserSeeder::class, '--force' => true]);
        $this->line(Artisan::output());

        $this->info('Seeding site content...');
        Artisan::call('db:seed', ['--class' => SiteContentSeeder::class, '--force' => true]);
        $this->line(Artisan::output());

        $this->newLine();
        $this->info('Site install complete.');
        $this->line('  Login: /login');
        $this->line('  baryan@gmail.com / havegood');
        $this->line('  admin@site.com / password');

        return self::SUCCESS;
    }

    private function runMigrations(): bool
    {
        if ($this->hasBrokenPartialMigration()) {
            $this->error('Database is in a broken state (some tables exist, migrations incomplete).');
            $this->line('Run this instead:');
            $this->line('  php artisan site:install --fresh --no-interaction');

            return false;
        }

        Artisan::call('migrate', ['--force' => true]);
        $output = Artisan::output();
        $this->line($output);

        if (str_contains($output, 'FAIL') || str_contains($output, 'already exists')) {
            $this->error('Migration failed. If tables were partially created, use --fresh.');
            $this->line('  php artisan site:install --fresh --no-interaction');

            return false;
        }

        return true;
    }

    private function hasBrokenPartialMigration(): bool
    {
        if (! Schema::hasTable('users')) {
            return false;
        }

        return ! Schema::hasTable('migrations')
            || ! Schema::hasTable('settings')
            || ! Schema::hasTable('cache');
    }
}

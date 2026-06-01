<?php

namespace App\Console\Commands;

use App\Services\BunnyStorageService;
use Illuminate\Console\Command;

class VerifyBunnyStorageCommand extends Command
{
    protected $signature = 'bunny:verify';

    protected $description = 'Test Bunny.net storage zone API credentials and CDN URL configuration';

    public function handle(BunnyStorageService $bunny): int
    {
        $this->info('Checking Bunny.net configuration...');
        $this->line('  Zone: '.(config('bunny.storage_zone') ?: '(not set)'));
        $this->line('  Host: '.config('bunny.storage_hostname'));
        $this->line('  CDN:  '.(config('bunny.cdn_url') ?: '(not set)'));

        $result = $bunny->verifyConnection();

        if ($result['ok']) {
            $this->newLine();
            $this->info($result['message']);

            return self::SUCCESS;
        }

        $this->newLine();
        $this->error($result['message']);

        return self::FAILURE;
    }
}

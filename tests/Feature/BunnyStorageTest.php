<?php

namespace Tests\Feature;

use App\Services\BunnyStorageService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class BunnyStorageTest extends TestCase
{
    public function test_verify_reports_missing_cdn_url(): void
    {
        config([
            'bunny.storage_zone' => 'basda',
            'bunny.storage_password' => 'test-key',
            'bunny.cdn_url' => '',
        ]);

        $result = app(BunnyStorageService::class)->verifyConnection();

        $this->assertFalse($result['ok']);
        $this->assertStringContainsString('BUNNY_CDN_URL', $result['message']);
    }

    public function test_verify_succeeds_when_storage_api_accepts_upload(): void
    {
        config([
            'bunny.storage_zone' => 'basda',
            'bunny.storage_password' => 'test-key',
            'bunny.cdn_url' => 'https://basda.b-cdn.net',
            'bunny.storage_hostname' => 'storage.bunnycdn.com',
        ]);

        Http::fake([
            'https://storage.bunnycdn.com/basda/*' => Http::sequence()
                ->push('', 201)
                ->push('', 200),
        ]);

        $result = app(BunnyStorageService::class)->verifyConnection();

        $this->assertTrue($result['ok']);
    }
}

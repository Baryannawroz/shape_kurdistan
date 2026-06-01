<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class BunnyStorageService
{
    public function isConfigured(): bool
    {
        return config('bunny.storage_zone')
            && config('bunny.storage_password')
            && config('bunny.cdn_url');
    }

    public function storageEndpoint(string $path): string
    {
        $zone = config('bunny.storage_zone');
        $host = config('bunny.storage_hostname', 'storage.bunnycdn.com');

        return sprintf(
            'https://%s/%s/%s',
            $host,
            $zone,
            ltrim($path, '/'),
        );
    }

    public function publicUrl(string $path): string
    {
        return rtrim((string) config('bunny.cdn_url'), '/').'/'.ltrim($path, '/');
    }

    public function upload(UploadedFile $file, string $directory = 'videos'): string
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException('Bunny.net is not configured. Set BUNNY_STORAGE_ZONE, BUNNY_STORAGE_PASSWORD, and BUNNY_CDN_URL in .env.');
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'mp4');
        $path = $directory.'/'.Str::uuid()->toString().'.'.$extension;

        $response = Http::withHeaders([
            'AccessKey' => config('bunny.storage_password'),
        ])
            ->withBody((string) file_get_contents($file->getRealPath()), (string) ($file->getMimeType() ?: 'application/octet-stream'))
            ->put($this->storageEndpoint($path));

        if ($response->failed()) {
            throw new RequestException($response);
        }

        return $path;
    }

    /**
     * @return array{ok: bool, message: string, cdn_url?: string}
     */
    public function verifyConnection(): array
    {
        if (! config('bunny.storage_zone') || ! config('bunny.storage_password')) {
            return ['ok' => false, 'message' => 'Missing BUNNY_STORAGE_ZONE or BUNNY_STORAGE_PASSWORD in .env.'];
        }

        if (! config('bunny.cdn_url')) {
            return ['ok' => false, 'message' => 'Missing BUNNY_CDN_URL in .env. Create a Pull Zone in bunny.net → CDN and paste its URL (e.g. https://basda.b-cdn.net).'];
        }

        $testPath = '.connection-test-'.Str::uuid()->toString().'.txt';

        try {
            $put = Http::withHeaders(['AccessKey' => config('bunny.storage_password')])
                ->withBody('ok', 'text/plain')
                ->put($this->storageEndpoint($testPath));

            if ($put->failed()) {
                return [
                    'ok' => false,
                    'message' => 'Storage API rejected the request (HTTP '.$put->status().'). Check zone name and password (use the main API password, not read-only).',
                ];
            }

            Http::withHeaders(['AccessKey' => config('bunny.storage_password')])
                ->delete($this->storageEndpoint($testPath));

            return [
                'ok' => true,
                'message' => 'Bunny storage and CDN URL are configured. Public files will use: '.config('bunny.cdn_url'),
                'cdn_url' => (string) config('bunny.cdn_url'),
            ];
        } catch (\Throwable $e) {
            return ['ok' => false, 'message' => $e->getMessage()];
        }
    }
}

<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class VideoUploadService
{
    public function __construct(private BunnyStorageService $bunnyStorage) {}

    public function store(UploadedFile $file, string $directory = 'videos'): string
    {
        if ($this->usesBunny()) {
            return $this->bunnyStorage->upload($file, $directory);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: 'mp4');
        $basename = Str::uuid()->toString().'.'.$extension;

        return $file->storeAs($directory, $basename, 'public');
    }

    public function publicUrl(string $path): string
    {
        if ($this->usesBunny()) {
            return $this->bunnyStorage->publicUrl($path);
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }

    public function usesBunny(): bool
    {
        return config('bunny.video_disk') === 'bunny' && $this->bunnyStorage->isConfigured();
    }
}

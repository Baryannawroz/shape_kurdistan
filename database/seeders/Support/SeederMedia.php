<?php

namespace Database\Seeders\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class SeederMedia
{
    /**
     * @var array<string, string>|null
     */
    private static ?array $paths = null;

    /**
     * @return array{hero: string, work: string, team: string, code: string}
     */
    public static function paths(): array
    {
        if (self::$paths !== null) {
            return self::$paths;
        }

        self::$paths = [
            'hero' => self::publish('hero-studio.jpg'),
            'work' => self::publish('showcase-work.jpg'),
            'team' => self::publish('team-portrait.jpg'),
            'code' => self::publish('project-code.jpg'),
        ];

        return self::$paths;
    }

    public static function publish(string $filename, string $directory = 'seed'): string
    {
        $source = database_path('seeders/assets/'.$filename);

        if (! File::isFile($source)) {
            throw new \RuntimeException("Seed asset missing: {$filename}");
        }

        $disk = Storage::disk('public');
        $path = $directory.'/'.$filename;
        $thumbPath = $directory.'/thumbs/'.$filename;

        $disk->makeDirectory($directory);
        $disk->makeDirectory($directory.'/thumbs');

        if (! $disk->exists($path)) {
            $disk->put($path, File::get($source));
        }

        if (! $disk->exists($thumbPath)) {
            $disk->put($thumbPath, File::get($source));
        }

        return $path;
    }
}

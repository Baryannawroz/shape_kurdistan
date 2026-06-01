<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AutoEncoder;
use Intervention\Image\ImageManager;

class ImageUploadService
{
    /**
     * @return array{path: string, thumb_path: string, url: string, thumb_url: string}
     */
    public function storeWithThumbnail(UploadedFile $file, string $directory, int $maxWidth = 1600, int $thumbWidth = 400): array
    {
        $disk = 'public';
        $extension = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $basename = Str::uuid()->toString().'.'.$extension;
        $path = $directory.'/'.$basename;
        $thumbPath = $directory.'/thumbs/'.$basename;

        if (in_array($extension, ['svg', 'ico'], true)) {
            Storage::disk($disk)->putFileAs($directory, $file, $basename);
            Storage::disk($disk)->putFileAs($directory.'/thumbs', $file, $basename);

            return [
                'path' => $path,
                'thumb_path' => $thumbPath,
                'url' => Storage::disk($disk)->url($path),
                'thumb_url' => Storage::disk($disk)->url($thumbPath),
            ];
        }

        $manager = new ImageManager(new Driver);
        $image = $manager->read($file->getRealPath());
        $image->scaleDown(width: $maxWidth);
        Storage::disk($disk)->put($path, (string) $image->encode(new AutoEncoder));

        $thumb = $manager->read($file->getRealPath())->scaleDown(width: $thumbWidth);
        Storage::disk($disk)->put($thumbPath, (string) $thumb->encode(new AutoEncoder));

        return [
            'path' => $path,
            'thumb_path' => $thumbPath,
            'url' => Storage::disk($disk)->url($path),
            'thumb_url' => Storage::disk($disk)->url($thumbPath),
        ];
    }
}

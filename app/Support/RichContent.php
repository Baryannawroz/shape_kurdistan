<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

class RichContent
{
    private const VIDEO_PATH_PATTERN = '/^videos\/[a-zA-Z0-9._-]+\.(?:mp4|webm|mov)$/i';

    private const YOUTUBE_ID_PATTERN = '/^[a-zA-Z0-9_-]{11}$/';

    private const HOSTED_SHORTCODE_PATTERN = '/\[\[video:(videos\/[a-zA-Z0-9._-]+\.(?:mp4|webm|mov))\]\]/i';

    private const YOUTUBE_SHORTCODE_PATTERN = '/\[\[(?:video|youtube):([a-zA-Z0-9_-]{11})\]\]/i';

    public static function expand(?string $html): ?string
    {
        if ($html === null || $html === '') {
            return $html;
        }

        $html = self::stripLegacyEmbeds($html);

        $html = (string) preg_replace_callback(
            self::YOUTUBE_SHORTCODE_PATTERN,
            static function (array $matches): string {
                $videoId = $matches[1];

                if (! preg_match(self::YOUTUBE_ID_PATTERN, $videoId)) {
                    return $matches[0];
                }

                return self::renderYoutubePlaceholder($videoId);
            },
            $html,
        );

        return (string) preg_replace_callback(
            self::HOSTED_SHORTCODE_PATTERN,
            static function (array $matches): string {
                $path = $matches[1];

                if (! preg_match(self::VIDEO_PATH_PATTERN, $path)) {
                    return $matches[0];
                }

                return self::renderHostedPlaceholder($path);
            },
            $html,
        );
    }

    public static function isValidVideoPath(string $path): bool
    {
        return (bool) preg_match(self::VIDEO_PATH_PATTERN, $path);
    }

    public static function videoUrl(string $path): string
    {
        if (config('bunny.video_disk') === 'bunny' && config('bunny.cdn_url')) {
            return rtrim((string) config('bunny.cdn_url'), '/').'/'.ltrim($path, '/');
        }

        return Storage::disk('public')->url($path);
    }

    private static function stripLegacyEmbeds(string $html): string
    {
        return (string) preg_replace(
            '/<div class="embed-video" data-video-id="[^"]+"><\/div>|<div class="embed-video-player[^>]*>.*?<\/iframe>.*?<\/div>\s*<\/div>/is',
            '',
            $html,
        );
    }

    private static function renderHostedPlaceholder(string $path): string
    {
        $src = e(self::videoUrl($path));

        return <<<HTML
        <div class="hosted-video" data-video-src="{$src}"></div>
        HTML;
    }

    private static function renderYoutubePlaceholder(string $videoId): string
    {
        $id = e($videoId);

        return <<<HTML
        <div class="youtube-video" data-youtube-id="{$id}"></div>
        HTML;
    }
}

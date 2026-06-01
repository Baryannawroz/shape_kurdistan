<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Video storage disk
    |--------------------------------------------------------------------------
    |
    | "public" = local storage/app/public (default)
    | "bunny"  = Bunny.net storage zone + CDN pull zone
    |
    */
    'video_disk' => env('VIDEO_STORAGE_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Bunny storage zone (API / FTP)
    |--------------------------------------------------------------------------
    |
    | Storage hostname: https://storage.bunnycdn.com/{zone}/path/to/file.mp4
    | Use the main "Password" from FTP & API Access — not the read-only password.
    |
    */
    'storage_zone' => env('BUNNY_STORAGE_ZONE', 'basda'),

    'storage_hostname' => env('BUNNY_STORAGE_HOST', 'storage.bunnycdn.com'),

    'storage_password' => env('BUNNY_STORAGE_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Bunny CDN (pull zone) — public URLs for browsers
    |--------------------------------------------------------------------------
    |
    | Create a Pull Zone in bunny.net → CDN → linked to storage zone "basda".
    | Example: https://basda.b-cdn.net or your custom hostname.
    |
    */
    'cdn_url' => rtrim(env('BUNNY_CDN_URL', ''), '/'),

];

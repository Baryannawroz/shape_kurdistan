<?php

namespace App\Models;

use App\Support\SiteSettings;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    protected static function booted(): void
    {
        static::saved(function (): void {
            SiteSettings::forgetCache();
        });

        static::deleted(function (): void {
            SiteSettings::forgetCache();
        });
    }
}

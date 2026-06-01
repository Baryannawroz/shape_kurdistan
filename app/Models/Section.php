<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Section extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = [
        'name',
        'subtitle',
        'description',
        'about_content',
        'contact_address',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'contact_address' => 'array',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}

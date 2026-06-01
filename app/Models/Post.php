<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = [
        'title',
        'excerpt',
        'body',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'allow_comments' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}

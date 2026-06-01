<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Navigation extends Model
{
    use HasTranslations;

    protected $guarded = [];

    public $translatable = ['label'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Navigation::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Navigation::class, 'parent_id')->orderBy('order');
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasTranslations;

    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'gallery' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<ProjectTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(ProjectTranslation::class);
    }
}

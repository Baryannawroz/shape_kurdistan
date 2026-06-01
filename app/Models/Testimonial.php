<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Testimonial extends Model
{
    use HasTranslations;

    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * @return HasMany<TestimonialTranslation, $this>
     */
    public function translations(): HasMany
    {
        return $this->hasMany(TestimonialTranslation::class);
    }
}

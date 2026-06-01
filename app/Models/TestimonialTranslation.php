<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestimonialTranslation extends Model
{
    protected $guarded = [];

    /**
     * @return BelongsTo<Testimonial, $this>
     */
    public function testimonial(): BelongsTo
    {
        return $this->belongsTo(Testimonial::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCategoryTranslation extends Model
{
    protected $guarded = [];

    /**
     * @return BelongsTo<ProductCategory, $this>
     */
    public function productCategory(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class);
    }
}

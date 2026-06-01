<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property-read \Illuminate\Support\Collection<int, Model> $translations
 */
trait HasTranslations
{
    /**
     * @return HasMany<Model, $this>
     */
    abstract public function translations(): HasMany;

    public function getTranslation(?string $locale = null): ?Model
    {
        $locale ??= app()->getLocale();
        $fallback = config('app.fallback_locale');

        if (! $this->relationLoaded('translations')) {
            $this->load('translations');
        }

        $translation = $this->translations->firstWhere('locale', $locale);

        if ($translation) {
            return $translation;
        }

        return $this->translations->firstWhere('locale', $fallback);
    }
}

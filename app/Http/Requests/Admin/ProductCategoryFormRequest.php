<?php

namespace App\Http\Requests\Admin;

use App\Models\ProductCategoryTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductCategoryFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasAnyRole(['super-admin', 'admin', 'editor']);
    }

    /**
     * @return array<string, array<int, string>|string>
     */
    public function rules(): array
    {
        return [
            'image' => ['nullable', 'image', 'max:8192'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
            'translations' => ['required', 'array'],
            'translations.*.locale' => ['required', 'string', Rule::in(array_keys(config('app.locales')))],
            'translations.*.slug' => ['required', 'string', 'max:255'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $categoryId = $this->route('product_category')?->id;

            foreach ($this->input('translations', []) as $index => $row) {
                $locale = $row['locale'] ?? null;
                $slug = $row['slug'] ?? null;

                if (! $locale || ! $slug) {
                    continue;
                }

                $query = ProductCategoryTranslation::query()->where('locale', $locale)->where('slug', $slug);

                if ($categoryId) {
                    $query->where('product_category_id', '!=', $categoryId);
                }

                if ($query->exists()) {
                    $validator->errors()->add(
                        "translations.$index.slug",
                        __('The slug has already been taken for this locale.')
                    );
                }
            }
        });
    }
}

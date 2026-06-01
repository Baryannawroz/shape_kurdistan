<?php

namespace App\Http\Requests\Admin;

use App\Models\ProjectTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProjectFormRequest extends FormRequest
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
            'client' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'integer', 'min:1970', 'max:2100'],
            'image' => ['nullable', 'image', 'max:8192'],
            'gallery' => ['nullable', 'array'],
            'gallery.*' => ['nullable', 'image', 'max:8192'],
            'category' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
            'translations' => ['required', 'array'],
            'translations.*.locale' => ['required', 'string', Rule::in(array_keys(config('app.locales')))],
            'translations.*.slug' => ['required', 'string', 'max:255'],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.description' => ['nullable', 'string'],
            'translations.*.tags' => ['nullable', 'array'],
            'translations.*.tags.*' => ['string', 'max:100'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $projectId = $this->route('project')?->id;

            foreach ($this->input('translations', []) as $index => $row) {
                $locale = $row['locale'] ?? null;
                $slug = $row['slug'] ?? null;

                if (! $locale || ! $slug) {
                    continue;
                }

                $query = ProjectTranslation::query()->where('locale', $locale)->where('slug', $slug);

                if ($projectId) {
                    $query->where('project_id', '!=', $projectId);
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

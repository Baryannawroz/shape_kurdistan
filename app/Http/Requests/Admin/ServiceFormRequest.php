<?php

namespace App\Http\Requests\Admin;

use App\Models\ServiceTranslation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ServiceFormRequest extends FormRequest
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
            'icon' => ['nullable', 'file', 'max:4096'],
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
            $serviceId = $this->route('service')?->id;

            foreach ($this->input('translations', []) as $index => $row) {
                $locale = $row['locale'] ?? null;
                $slug = $row['slug'] ?? null;

                if (! $locale || ! $slug) {
                    continue;
                }

                $query = ServiceTranslation::query()->where('locale', $locale)->where('slug', $slug);

                if ($serviceId) {
                    $query->where('service_id', '!=', $serviceId);
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

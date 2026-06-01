<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TestimonialFormRequest extends FormRequest
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
            'avatar' => ['nullable', 'image', 'max:4096'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
            'translations' => ['required', 'array'],
            'translations.*.locale' => ['required', 'string', Rule::in(array_keys(config('app.locales')))],
            'translations.*.author_name' => ['required', 'string', 'max:255'],
            'translations.*.company' => ['nullable', 'string', 'max:255'],
            'translations.*.content' => ['required', 'string'],
        ];
    }
}

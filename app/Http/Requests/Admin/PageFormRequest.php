<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PageFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasAnyRole(['super-admin', 'admin', 'editor']);
    }

    /**
     * @return array<string, array<int, \Illuminate\Validation\Rules\Unique|string>|string>
     */
    public function rules(): array
    {
        $pageId = $this->route('page')?->id;

        return [
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('pages', 'slug')->ignore($pageId),
            ],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
            'translations' => ['required', 'array'],
            'translations.*.locale' => ['required', 'string', Rule::in(array_keys(config('app.locales')))],
            'translations.*.title' => ['required', 'string', 'max:255'],
            'translations.*.content' => ['nullable', 'string'],
            'translations.*.meta_title' => ['nullable', 'string', 'max:255'],
            'translations.*.meta_description' => ['nullable', 'string', 'max:500'],
        ];
    }
}

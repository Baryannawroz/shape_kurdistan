<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamMemberFormRequest extends FormRequest
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
            'photo' => ['nullable', 'image', 'max:8192'],
            'role_key' => ['nullable', 'string', 'max:100'],
            'linkedin' => ['nullable', 'url', 'max:255'],
            'is_active' => ['boolean'],
            'order' => ['integer', 'min:0'],
            'translations' => ['required', 'array'],
            'translations.*.locale' => ['required', 'string', Rule::in(array_keys(config('app.locales')))],
            'translations.*.name' => ['required', 'string', 'max:255'],
            'translations.*.position' => ['nullable', 'string', 'max:255'],
            'translations.*.bio' => ['nullable', 'string'],
        ];
    }
}

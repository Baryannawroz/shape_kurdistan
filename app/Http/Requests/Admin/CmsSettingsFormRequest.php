<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CmsSettingsFormRequest extends FormRequest
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
            'settings' => ['required', 'array'],
            'settings.*' => ['nullable', 'string', 'max:65000'],
            'site_logo' => ['nullable', 'image', 'max:4096'],
            'site_favicon' => ['nullable', 'file', 'max:1024'],
            'seo_og_image' => ['nullable', 'image', 'max:8192'],
        ];
    }
}

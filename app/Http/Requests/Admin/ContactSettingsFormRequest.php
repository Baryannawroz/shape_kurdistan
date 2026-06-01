<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ContactSettingsFormRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->hasAnyRole(['super-admin', 'admin', 'editor']);
    }

    /**
     * @return array<string, array<int, \Illuminate\Validation\Rules\In|string>|string>
     */
    public function rules(): array
    {
        $localeCodes = array_keys(config('app.locales', []));

        return [
            'phone' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'maps_embed_url' => ['nullable', 'string', 'max:65000'],
            'address' => ['nullable', 'array'],
            ...collect($localeCodes)->mapWithKeys(fn (string $code): array => [
                'address.'.$code => ['nullable', 'string', 'max:2000'],
            ])->all(),
        ];
    }

    protected function prepareForValidation(): void
    {
        $allowed = array_keys(config('app.locales', []));
        $address = $this->input('address', []);
        if (! is_array($address)) {
            $address = [];
        }

        $filtered = [];
        foreach ($allowed as $code) {
            $filtered[$code] = $address[$code] ?? null;
        }

        $this->merge(['address' => $filtered]);
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.email' => __('validation.email', ['attribute' => __('Email')]),
        ];
    }
}

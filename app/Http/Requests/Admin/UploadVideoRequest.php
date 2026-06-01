<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UploadVideoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'video' => ['required', 'file', 'mimetypes:video/mp4,video/webm,video/quicktime', 'max:204800'],
        ];
    }
}

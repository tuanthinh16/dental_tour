<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LandingHeroImageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'eyebrow' => ['required', 'string', 'max:120'],
            'title_line_1' => ['required', 'string', 'max:160'],
            'title_before_image' => ['required', 'string', 'max:120'],
            'title_after_image' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'max:500'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ];
    }
}

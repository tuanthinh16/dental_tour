<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LandingDestinationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if (app()->getLocale() !== 'vi') {
            return [
                'short_description' => ['nullable', 'string', 'max:1000'],
            ];
        }

        return [
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'image' => [$this->isMethod('post') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
        ];
    }
}

<?php

namespace App\Http\Requests\Admin;

use App\Support\ThemeOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'ui_color_primary' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ui_color_accent' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ui_color_background' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ui_color_text' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ui_color_surface' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'ui_font_header' => ['required', Rule::in(ThemeOptions::fontNames())],
            'ui_font_title' => ['required', Rule::in(ThemeOptions::fontNames())],
            'ui_font_body' => ['required', Rule::in(ThemeOptions::fontNames())],
        ];
    }
}

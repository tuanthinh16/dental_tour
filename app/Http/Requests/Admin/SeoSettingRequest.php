<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SeoSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'seo_site_title' => ['required', 'string', 'max:255'],
            'seo_site_description' => ['required', 'string', 'max:500'],
            'seo_keywords' => ['required', 'string', 'max:1000'],
            'seo_og_image' => ['nullable', 'url', 'max:2048'],
            'seo_og_image_upload' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'seo_sitemap_urls' => [
                'nullable',
                'string',
                'max:10000',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    foreach (preg_split('/\R/', (string) $value) ?: [] as $path) {
                        $path = trim($path);
                        if ($path !== '' && ! str_starts_with($path, '/')) {
                            $fail('Mỗi đường dẫn sitemap phải bắt đầu bằng dấu /.');

                            return;
                        }
                    }
                },
            ],
        ];
    }
}

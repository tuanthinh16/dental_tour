<?php

namespace App\Http\Requests\Admin;

use App\Support\MoneyFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
            'currency' => MoneyFormatter::normalizeCurrency($this->input('currency')),
        ]);
    }

    public function rules(): array
    {
        $id = $this->route('product')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'short_description' => ['nullable', 'string', 'max:1500'],
            'description' => ['nullable', 'string'],
            'base_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'original_price' => ['nullable', 'numeric', 'gte:base_price', 'max:9999999999.99'],
            'currency' => ['required', Rule::in(array_keys(MoneyFormatter::SUPPORTED_CURRENCIES))],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
        ];
    }
}

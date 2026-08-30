<?php

namespace App\Http\Requests\Admin;

use App\Support\MoneyFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LandingTourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $values = [
            'currency' => MoneyFormatter::normalizeCurrency($this->input('currency')),
        ];

        if ($this->has('service_selection_submitted') && ! $this->has('service_ids')) {
            $values['service_ids'] = [];
        }

        if ($this->has('included_product_selection_submitted') && ! $this->has('included_product_ids')) {
            $values['included_product_ids'] = [];
        }

        $this->merge($values);
    }

    public function rules(): array
    {
        return [
            'destination_id' => [
                'nullable',
                'integer',
                Rule::exists('products', 'id')
                    ->where('product_type', 'destination')
                    ->whereNull('deleted_at'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'short_description' => ['required', 'string', 'max:1500'],
            'duration_days' => ['required', 'integer', 'min:1', 'max:365'],
            'base_price' => ['required', 'numeric', 'min:0', 'max:9999999999.99'],
            'currency' => ['required', Rule::in(array_keys(MoneyFormatter::SUPPORTED_CURRENCIES))],
            'image' => [$this->isMethod('post') ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:8192'],
            'service_ids' => ['nullable', 'array'],
            'service_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('categories', 'id')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
            ],
            'included_product_ids' => ['nullable', 'array'],
            'included_product_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('products', 'id')
                    ->where('product_type', 'addon')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}

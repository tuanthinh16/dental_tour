<?php

namespace App\Http\Requests\Admin;

use App\Support\MoneyFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TourRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $values = [
            'is_active' => $this->boolean('is_active'),
            'is_featured' => $this->boolean('is_featured'),
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
        $id = $this->route('tour')?->id;

        return [
            'destination_id' => [
                'nullable',
                Rule::exists('products', 'id')
                    ->where('product_type', 'destination')
                    ->whereNull('deleted_at'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('products', 'slug')->ignore($id),
            ],
            'short_description' => ['required', 'string'],
            'description' => ['required', 'string'],
            'duration_days' => ['required', 'integer', 'min:1'],
            'duration_nights' => ['nullable', 'integer', 'min:0'],
            'base_price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],
            'original_price' => [
                'nullable',
                'numeric',
                'gte:base_price',
                'max:9999999999.99',
            ],
            'currency' => ['required', Rule::in(array_keys(MoneyFormatter::SUPPORTED_CURRENCIES))],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'badge' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'is_active' => ['boolean'],
            'itineraries' => ['nullable', 'array'],
            'itineraries.*.day_number' => ['required', 'integer', 'min:1'],
            'itineraries.*.title' => ['required', 'string', 'max:255'],
            'itineraries.*.description' => ['required', 'string'],
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
            'excluded_items' => ['nullable', 'array'],
            'excluded_items.*.content' => ['required', 'string'],
        ];
    }
}

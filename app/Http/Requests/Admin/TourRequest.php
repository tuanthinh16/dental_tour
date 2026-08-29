<?php

namespace App\Http\Requests\Admin;

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
        ];

        if ($this->has('service_selection_submitted') && ! $this->has('service_ids')) {
            $values['service_ids'] = [];
        }

        $this->merge($values);
    }

    public function rules(): array
    {
        $id = $this->route('tour')?->id;

        return [
            'category_id' => ['nullable', 'exists:tour_categories,id'],
            'destination_id' => ['nullable', 'exists:destinations,id'],
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'alpha_dash',
                'max:255',
                Rule::unique('tours')->ignore($id),
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
            'currency' => ['required', 'string', 'size:3'],
            'image_id' => ['nullable', 'exists:media,id'],
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
                Rule::exists('tour_services', 'id')
                    ->where('is_active', true)
                    ->whereNull('deleted_at'),
            ],
            'excluded_items' => ['nullable', 'array'],
            'excluded_items.*.content' => ['required', 'string'],
        ];
    }
}

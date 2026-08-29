<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class TourCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge(["is_active" => $this->boolean("is_active")]);
    }
    public function rules(): array
    {
        $id = $this->route("tour_category")?->id;
        return [
            "name" => ["required", "string", "max:255"],
            "slug" => [
                "required",
                "alpha_dash",
                "max:255",
                Rule::unique("tour_categories")->ignore($id),
            ],
            "sort_order" => ["required", "integer", "min:0"],
            "is_active" => ["boolean"],
        ];
    }
}

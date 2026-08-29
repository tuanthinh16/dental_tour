<?php
namespace App\Http\Requests\Admin;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class PageRequest extends FormRequest
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
        $id = $this->route("page")?->id;
        return [
            "title" => ["required", "string", "max:255"],
            "slug" => [
                "required",
                "alpha_dash",
                "max:255",
                Rule::unique("pages")->ignore($id),
            ],
            "content" => ["required", "string"],
            "seo_title" => ["nullable", "string", "max:255"],
            "seo_description" => ["nullable", "string", "max:500"],
            "is_active" => ["boolean"],
        ];
    }
}

<?php
namespace App\Http\Requests\Web;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            "utm_source" => $this->input(
                "utm_source",
                $this->query("utm_source"),
            ),
            "utm_medium" => $this->input(
                "utm_medium",
                $this->query("utm_medium"),
            ),
            "utm_campaign" => $this->input(
                "utm_campaign",
                $this->query("utm_campaign"),
            ),
        ]);
    }
    public function rules(): array
    {
        return [
            "full_name" => ["required", "string", "max:255"],
            "email" => ["required", "email", "max:255"],
            "phone" => ["required", "string", "max:30"],
            "country" => ["nullable", "string", "max:255"],
            "tour_id" => [
                "nullable",
                "integer",
                Rule::exists("tours", "id")->where(
                    fn($query) => $query
                        ->where("is_active", true)
                        ->whereNull("deleted_at"),
                ),
            ],
            "travel_date" => ["nullable", "date", "after_or_equal:today"],
            "number_of_people" => ["nullable", "integer", "min:1", "max:1000"],
            "message" => ["nullable", "string", "max:5000"],
            "utm_source" => ["nullable", "string", "max:255"],
            "utm_medium" => ["nullable", "string", "max:255"],
            "utm_campaign" => ["nullable", "string", "max:255"],
        ];
    }
}

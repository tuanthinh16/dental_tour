<?php
namespace App\Http\Requests\Admin;
use App\Models\ConsultationRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateConsultationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            "status" => ["required", Rule::in(ConsultationRequest::STATUSES)],
            "message" => ["nullable", "string", "max:5000"],
        ];
    }
}

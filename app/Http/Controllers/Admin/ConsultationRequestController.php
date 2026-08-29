<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateConsultationRequest;
use App\Models\ConsultationRequest;
use App\Services\ConsultationService;
use Illuminate\Support\Facades\Log;
class ConsultationRequestController extends Controller
{
    public function __construct(private ConsultationService $service) {}
    public function index()
    {
        return view("admin.consultations.index", [
            "items" => $this->service->repository->paginate(),
        ]);
    }
    public function edit(ConsultationRequest $consultation)
    {
        return view("admin.consultations.edit", [
            "item" => $consultation->load("tour"),
        ]);
    }
    public function update(
        UpdateConsultationRequest $r,
        ConsultationRequest $consultation,
    ) {
        $this->service->update($consultation, $r->validated());
        return redirect()
            ->route("admin.consultations.index")
            ->with("success", "Đã cập nhật yêu cầu.");
    }
    public function destroy(ConsultationRequest $consultation)
    {
        $this->service->repository->delete($consultation);
        Log::info("Admin consultation deleted", [
            "consultation_id" => $consultation->id,
        ]);
        return back()->with("success", "Đã xóa yêu cầu.");
    }
}

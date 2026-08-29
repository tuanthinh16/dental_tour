<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\StoreConsultationRequest;
use App\Services\ConsultationService;
class ConsultationController extends Controller
{
    public function __construct(private ConsultationService $service) {}
    public function store(StoreConsultationRequest $request)
    {
        $this->service->create($request->validated());
        return back()->with(
            "success",
            "Cảm ơn bạn! Chúng tôi sẽ liên hệ tư vấn trong thời gian sớm nhất.",
        );
    }
}

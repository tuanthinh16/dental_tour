<?php
namespace App\Services;
use App\Models\ConsultationRequest;
use App\Repositories\ConsultationRepository;
use Illuminate\Support\Facades\Log;
class ConsultationService
{
    public function __construct(public ConsultationRepository $repository) {}
    public function create(array $data): ConsultationRequest
    {
        $item = $this->repository->create($data + ["status" => "new"]);
        Log::info("New consultation", [
            "consultation_id" => $item->id,
            "tour_id" => $item->tour_id,
        ]);
        return $item;
    }
    public function update(
        ConsultationRequest $item,
        array $data,
    ): ConsultationRequest {
        $saved = $this->repository->update($item, $data);
        Log::info("Admin consultation updated", [
            "consultation_id" => $item->id,
            "status" => $saved->status,
        ]);
        return $saved;
    }
}

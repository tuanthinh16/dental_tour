<?php
namespace App\Repositories;
use App\Models\ConsultationRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class ConsultationRepository
{
    public function paginate(int $perPage = 20): LengthAwarePaginator
    {
        return ConsultationRequest::with("tour")->latest()->paginate($perPage);
    }
    public function create(array $data): ConsultationRequest
    {
        return ConsultationRequest::create($data);
    }
    public function update(
        ConsultationRequest $item,
        array $data,
    ): ConsultationRequest {
        $item->update($data);
        return $item;
    }
    public function delete(ConsultationRequest $item): void
    {
        $item->delete();
    }
}

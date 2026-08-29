<?php
namespace App\Services;
use App\Models\Destination;
use App\Repositories\DestinationRepository;
use Illuminate\Support\Facades\Log;
class DestinationService
{
    public function __construct(public DestinationRepository $repository) {}
    public function save(array $data, ?Destination $item = null): Destination
    {
        $saved = $item
            ? $this->repository->update($item, $data)
            : $this->repository->create($data);
        Log::info("Admin destination saved", ["destination_id" => $saved->id]);
        return $saved;
    }
    public function delete(Destination $item): void
    {
        $this->repository->delete($item);
        Log::info("Admin destination deleted", ["destination_id" => $item->id]);
    }
}

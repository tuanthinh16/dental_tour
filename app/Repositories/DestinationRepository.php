<?php
namespace App\Repositories;
use App\Models\Destination;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
class DestinationRepository
{
    public function active(): Collection
    {
        return Destination::with("image")
            ->withCount([
                "tours" => fn($query) => $query->where("is_active", true),
            ])
            ->where("is_active", true)
            ->orderBy("sort_order")
            ->get();
    }
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Destination::with("image")
            ->orderBy("sort_order")
            ->paginate($perPage);
    }
    public function create(array $data): Destination
    {
        return Destination::create($data);
    }
    public function update(Destination $item, array $data): Destination
    {
        $item->update($data);
        return $item;
    }
    public function delete(Destination $item): void
    {
        $item->delete();
    }
}

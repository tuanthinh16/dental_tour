<?php
namespace App\Repositories;
use App\Models\Page;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
class PageRepository
{
    public function paginate(int $perPage = 15): LengthAwarePaginator
    {
        return Page::latest()->paginate($perPage);
    }
    public function create(array $data): Page
    {
        return Page::create($data);
    }
    public function update(Page $item, array $data): Page
    {
        $item->update($data);
        return $item;
    }
    public function delete(Page $item): void
    {
        $item->delete();
    }
}

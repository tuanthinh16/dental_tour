<?php
namespace App\Services;
use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Support\Facades\Log;
class PageService
{
    public function __construct(public PageRepository $repository) {}
    public function save(array $data, ?Page $item = null): Page
    {
        $saved = $item
            ? $this->repository->update($item, $data)
            : $this->repository->create($data);
        Log::info("Admin page saved", ["page_id" => $saved->id]);
        return $saved;
    }
    public function delete(Page $item): void
    {
        $this->repository->delete($item);
        Log::info("Admin page deleted", ["page_id" => $item->id]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DestinationRequest;
use App\Models\Destination;
use App\Services\DestinationService;

class DestinationController extends Controller
{
    public function __construct(private DestinationService $service) {}

    public function index()
    {
        return view('admin.crud.index', [
            'title' => 'Điểm đến',
            'items' => $this->service->repository->paginate(),
            'columns' => [
                'name' => 'Tên',
                'slug' => 'Slug',
                'is_active' => 'Hoạt động',
            ],
            'route' => 'destinations',
            'createFields' => $this->fields(),
        ]);
    }

    public function create()
    {
        return view('admin.crud.form', [
            'title' => 'Thêm điểm đến',
            'route' => 'destinations',
            'item' => new Destination,
            'fields' => $this->fields(),
        ]);
    }

    public function store(DestinationRequest $r)
    {
        $this->service->save($r->validated());

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Đã tạo điểm đến.');
    }

    public function edit(Destination $destination)
    {
        return view('admin.crud.form', [
            'title' => 'Sửa điểm đến',
            'route' => 'destinations',
            'item' => $destination,
            'fields' => $this->fields(),
        ]);
    }

    public function update(DestinationRequest $r, Destination $destination)
    {
        $this->service->save($r->validated(), $destination);

        return redirect()
            ->route('admin.destinations.index')
            ->with('success', 'Đã cập nhật điểm đến.');
    }

    public function destroy(Destination $destination)
    {
        $this->service->delete($destination);

        return back()->with('success', 'Đã xóa điểm đến.');
    }

    private function fields(): array
    {
        return [
            'name' => ['label' => 'Tên', 'type' => 'text', 'required' => true],
            'slug' => ['label' => 'Slug', 'type' => 'text', 'required' => true],
            'short_description' => [
                'label' => 'Mô tả ngắn',
                'type' => 'textarea',
            ],
            'description' => ['label' => 'Mô tả', 'type' => 'textarea'],
            'image' => ['label' => 'Ảnh điểm đến', 'type' => 'image'],
            'sort_order' => [
                'label' => 'Thứ tự',
                'type' => 'number',
                'default' => 0,
                'required' => true,
            ],
            'is_active' => [
                'label' => 'Hoạt động',
                'type' => 'checkbox',
                'default' => 1,
            ],
        ];
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TourRequest;
use App\Models\Destination;
use App\Models\IncludedService;
use App\Models\Tour;
use App\Models\TourCategory;
use App\Services\TourService;

class TourController extends Controller
{
    public function __construct(private TourService $service) {}

    public function index()
    {
        return view('admin.crud.index', [
            'title' => 'Tour',
            'items' => $this->service->repository->paginate(),
            'columns' => [
                'name' => 'Tên',
                'base_price' => 'Giá',
                'currency' => 'Tiền tệ',
                'is_active' => 'Hoạt động',
            ],
            'route' => 'tours',
        ]);
    }

    public function create()
    {
        return $this->form(new Tour, 'Thêm tour');
    }

    public function store(TourRequest $r)
    {
        $this->service->save($r->validated());

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Đã tạo tour.');
    }

    public function edit(Tour $tour)
    {
        $tour->load(['itineraries', 'services', 'excludedItems']);

        return $this->form($tour, 'Sửa tour');
    }

    public function update(TourRequest $r, Tour $tour)
    {
        $this->service->save($r->validated(), $tour);

        return redirect()
            ->route('admin.tours.index')
            ->with('success', 'Đã cập nhật tour.');
    }

    public function destroy(Tour $tour)
    {
        $this->service->delete($tour);

        return back()->with('success', 'Đã xóa tour.');
    }

    private function form(Tour $tour, string $title)
    {
        return view('admin.tours.form', [
            'item' => $tour,
            'title' => $title,
            'destinations' => Destination::where('is_active', true)
                ->orderBy('name')
                ->get(),
            'categories' => TourCategory::where('is_active', true)
                ->orderBy('name')
                ->get(),
            'services' => IncludedService::where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
        ]);
    }
}

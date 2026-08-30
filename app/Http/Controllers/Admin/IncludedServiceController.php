<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\IncludedServiceRequest;
use App\Models\IncludedService;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IncludedServiceController extends Controller
{
    public function index()
    {
        return view('admin.crud.index', [
            'title' => 'Dịch vụ đi kèm',
            'items' => IncludedService::orderBy('sort_order')->paginate(15),
            'columns' => [
                'name' => 'Tên dịch vụ',
                'sort_order' => 'Thứ tự',
                'is_active' => 'Hoạt động',
            ],
            'route' => 'included-services',
            'createFields' => $this->fields(),
        ]);
    }

    public function create()
    {
        return $this->form(new IncludedService, 'Thêm dịch vụ');
    }

    public function store(IncludedServiceRequest $request)
    {
        $service = IncludedService::create($request->validated());
        Log::info('Admin product category created', ['category_id' => $service->id]);

        return redirect()
            ->route('admin.included-services.index')
            ->with('success', 'Đã tạo dịch vụ.');
    }

    public function edit(IncludedService $includedService)
    {
        return $this->form($includedService, 'Sửa dịch vụ');
    }

    public function update(IncludedServiceRequest $request, IncludedService $includedService)
    {
        $includedService->update($request->validated());
        Log::info('Admin product category updated', ['category_id' => $includedService->id]);

        return redirect()
            ->route('admin.included-services.index')
            ->with('success', 'Đã cập nhật dịch vụ.');
    }

    public function destroy(IncludedService $includedService)
    {
        DB::transaction(function () use ($includedService): void {
            Tour::query()->get()->each(function (Tour $tour) use ($includedService): void {
                if (in_array($includedService->id, $tour->categoryIdList(), true)) {
                    $tour->syncCategoryIds(array_values(array_diff(
                        $tour->categoryIdList(),
                        [$includedService->id],
                    )));
                }
            });
            $includedService->delete();
        });
        Log::info('Admin product category deleted', ['category_id' => $includedService->id]);

        return back()->with('success', 'Đã xóa dịch vụ.');
    }

    private function form(IncludedService $item, string $title)
    {
        return view('admin.crud.form', [
            'item' => $item,
            'title' => $title,
            'route' => 'included-services',
            'fields' => $this->fields(),
        ]);
    }

    private function fields(): array
    {
        return [
            'name' => ['label' => 'Tên dịch vụ', 'type' => 'text', 'required' => true],
            'description' => ['label' => 'Mô tả', 'type' => 'textarea'],
            'sort_order' => ['label' => 'Thứ tự', 'type' => 'number', 'default' => 0, 'required' => true],
            'is_active' => ['label' => 'Hoạt động', 'type' => 'checkbox', 'default' => 1],
        ];
    }
}

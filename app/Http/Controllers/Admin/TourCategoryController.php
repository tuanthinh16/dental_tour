<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TourCategoryRequest;
use App\Models\TourCategory;
use Illuminate\Support\Facades\Log;
class TourCategoryController extends Controller
{
    public function index()
    {
        return view("admin.crud.index", [
            "title" => "Danh mục tour",
            "items" => TourCategory::orderBy("sort_order")->paginate(15),
            "columns" => [
                "name" => "Tên",
                "slug" => "Slug",
                "is_active" => "Hoạt động",
            ],
            "route" => "tour-categories",
        ]);
    }
    public function create()
    {
        return $this->form(new TourCategory(), "Thêm danh mục");
    }
    public function store(TourCategoryRequest $r)
    {
        $item = TourCategory::create($r->validated());
        Log::info("Admin tour category created", [
            "tour_category_id" => $item->id,
        ]);
        return redirect()
            ->route("admin.tour-categories.index")
            ->with("success", "Đã tạo danh mục.");
    }
    public function edit(TourCategory $tourCategory)
    {
        return $this->form($tourCategory, "Sửa danh mục");
    }
    public function update(TourCategoryRequest $r, TourCategory $tourCategory)
    {
        $tourCategory->update($r->validated());
        Log::info("Admin tour category updated", [
            "tour_category_id" => $tourCategory->id,
        ]);
        return redirect()
            ->route("admin.tour-categories.index")
            ->with("success", "Đã cập nhật danh mục.");
    }
    public function destroy(TourCategory $tourCategory)
    {
        $tourCategory->delete();
        Log::info("Admin tour category deleted", [
            "tour_category_id" => $tourCategory->id,
        ]);
        return back()->with("success", "Đã xóa danh mục.");
    }
    private function form($item, $title)
    {
        return view(
            "admin.crud.form",
            compact("item", "title") + [
                "route" => "tour-categories",
                "fields" => [
                    "name" => ["label" => "Tên", "type" => "text"],
                    "slug" => ["label" => "Slug", "type" => "text"],
                    "sort_order" => [
                        "label" => "Thứ tự",
                        "type" => "number",
                        "default" => 0,
                    ],
                    "is_active" => [
                        "label" => "Hoạt động",
                        "type" => "checkbox",
                        "default" => 1,
                    ],
                ],
            ],
        );
    }
}

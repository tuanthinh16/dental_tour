<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PageRequest;
use App\Models\Page;
use App\Services\PageService;
class PageController extends Controller
{
    public function __construct(private PageService $service) {}
    public function index()
    {
        return view("admin.crud.index", [
            "title" => "Trang nội dung",
            "items" => $this->service->repository->paginate(),
            "columns" => [
                "title" => "Tiêu đề",
                "slug" => "Slug",
                "is_active" => "Hoạt động",
            ],
            "route" => "pages",
        ]);
    }
    public function create()
    {
        return $this->form(new Page(), "Thêm trang");
    }
    public function store(PageRequest $r)
    {
        $this->service->save($r->validated());
        return redirect()
            ->route("admin.pages.index")
            ->with("success", "Đã tạo trang.");
    }
    public function edit(Page $page)
    {
        return $this->form($page, "Sửa trang");
    }
    public function update(PageRequest $r, Page $page)
    {
        $this->service->save($r->validated(), $page);
        return redirect()
            ->route("admin.pages.index")
            ->with("success", "Đã cập nhật trang.");
    }
    public function destroy(Page $page)
    {
        $this->service->delete($page);
        return back()->with("success", "Đã xóa trang.");
    }
    private function form(Page $item, string $title)
    {
        return view(
            "admin.crud.form",
            compact("item", "title") + [
                "route" => "pages",
                "fields" => [
                    "title" => ["label" => "Tiêu đề", "type" => "text"],
                    "slug" => ["label" => "Slug", "type" => "text"],
                    "content" => ["label" => "Nội dung", "type" => "textarea"],
                    "seo_title" => ["label" => "SEO title", "type" => "text"],
                    "seo_description" => [
                        "label" => "SEO description",
                        "type" => "textarea",
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

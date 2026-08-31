<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(private ProductService $service) {}

    public function index()
    {
        return view('admin.products.index', [
            'items' => Product::query()
                ->where('product_type', 'addon')
                ->with('image')
                ->orderBy('sort_order')
                ->orderBy('name')
                ->paginate(12)
                ->withQueryString(),
        ]);
    }

    public function store(ProductRequest $request)
    {
        $this->service->save($request->validated());

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã thêm sản phẩm.');
    }

    public function update(ProductRequest $request, Product $product)
    {
        $this->service->save($request->validated(), $this->addon($product));

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Đã cập nhật sản phẩm.');
    }

    public function destroy(Product $product)
    {
        $this->service->delete($this->addon($product));

        return back()->with('success', 'Đã xóa sản phẩm.');
    }

    private function addon(Product $product): Product
    {
        abort_unless($product->product_type === 'addon', 404);

        return $product;
    }
}

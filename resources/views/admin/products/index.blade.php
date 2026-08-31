@extends('layouts.admin')

@section('title', 'Sản phẩm')

@section('content')
    <section class="grain ink-contrast relative overflow-hidden bg-ink px-6 py-10 text-white md:px-10 md:py-14">
        <div class="hero-wash absolute inset-0 opacity-60"></div>
        <div class="relative">
            <div>
                <p class="text-sm font-semibold text-coral">Danh mục dùng chung</p>
                <h1 class="mt-4 max-w-6xl text-4xl font-semibold tracking-[-0.055em] md:text-6xl">Sản phẩm đi kèm</h1>
                <p class="mt-5 max-w-2xl text-sm leading-6 text-white/60">Quản lý các dịch vụ, vé và tiện ích có thể gán cho từng tour.</p>
            </div>
        </div>
    </section>

    <section data-product-catalog class="mt-5">
        <div class="flex items-center justify-end border border-ink/10 bg-sand p-2">
            <div class="flex border border-ink/15 bg-white p-1" role="group" aria-label="Kiểu hiển thị">
                <button type="button" data-product-view-switch="list" aria-pressed="true" class="bg-ink px-3 py-2 text-xs font-semibold text-white">Danh sách</button>
                <button type="button" data-product-view-switch="card" aria-pressed="false" class="px-3 py-2 text-xs font-semibold text-ink/60 transition-colors hover:text-ink">Thẻ</button>
            </div>
        </div>

        <div data-product-view="list" class="border-x border-b border-ink/10 bg-white">
            <div class="grid grid-cols-[4rem_minmax(0,1fr)_auto] items-center gap-4 border-b border-ink/10 bg-sand px-5 py-3 text-[10px] font-semibold uppercase tracking-[0.16em] text-ink/45 md:grid-cols-[4rem_minmax(14rem,1.5fr)_minmax(11rem,1fr)_8rem_8rem] md:px-7">
                <span>Ảnh</span><span>Sản phẩm</span><span class="hidden md:block">Giá</span><span class="hidden md:block">Trạng thái</span><span class="text-right">Thao tác</span>
            </div>
            @forelse($items as $item)
                <article data-motion-card class="group grid grid-cols-[4rem_minmax(0,1fr)_auto] items-center gap-4 border-b border-ink/10 px-5 py-4 last:border-b-0 md:grid-cols-[4rem_minmax(14rem,1.5fr)_minmax(11rem,1fr)_8rem_8rem] md:px-7">
                    <div class="size-14 overflow-hidden bg-cream">
                        @if($item->image?->file_path)
                            <img src="{{ $item->image->file_path }}" alt="{{ $item->image->alt_text ?: $item->name }}" class="h-full w-full object-cover grayscale-[15%] transition-transform duration-700 group-hover:scale-105">
                        @else
                            <span class="grid h-full place-items-center text-xs text-ink/35">—</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold text-ink" title="{{ $item->name }}">{{ $item->name }}</p>
                        <p class="mt-1 truncate text-xs text-ink/45">{{ $item->short_description ?: 'Chưa có mô tả ngắn' }}</p>
                    </div>
                    <p class="hidden text-sm font-semibold tracking-[-0.02em] text-ink md:block">{{ \App\Support\MoneyFormatter::format($item->base_price, $item->currency) }}</p>
                    <p class="hidden text-xs font-semibold md:block {{ $item->is_active ? 'text-forest' : 'text-ink/35' }}">{{ $item->is_active ? 'Hiển thị' : 'Đang ẩn' }}</p>
                    <div class="flex justify-end gap-2">
                        <button type="button" data-product-dialog-open="edit-product-{{ $item->id }}" class="border border-ink/20 px-3 py-2 text-xs font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Sửa</button>
                        <form method="post" action="{{ route('admin.products.destroy', $item) }}" onsubmit="return confirm('Xóa sản phẩm này?')">
                            @csrf
                            @method('DELETE')
                            <button class="px-2 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-50">Xóa</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="px-6 py-16 text-center">
                    <p class="text-lg font-semibold text-ink">Chưa có sản phẩm đi kèm.</p>
                    <a href="#create-products" class="accent-contrast mt-6 inline-flex rounded-full bg-coral px-6 py-3 text-sm font-semibold">Thêm sản phẩm đầu tiên</a>
                </div>
            @endforelse
        </div>

        <div data-product-view="card" hidden class="grid grid-flow-dense gap-px border-x border-b border-ink/10 bg-ink/10 sm:grid-cols-2 xl:grid-cols-3">
            @foreach($items as $item)
                <article data-motion-card class="group flex min-h-64 flex-col bg-white p-6">
                    <div class="flex items-start justify-between gap-4">
                        <div class="size-14 overflow-hidden bg-cream">
                            @if($item->image?->file_path)
                                <img src="{{ $item->image->file_path }}" alt="{{ $item->image->alt_text ?: $item->name }}" class="h-full w-full object-cover grayscale-[15%] transition-transform duration-700 group-hover:scale-105">
                            @else
                                <span class="grid h-full place-items-center text-xs text-ink/35">—</span>
                            @endif
                        </div>
                        <span class="text-xs font-semibold {{ $item->is_active ? 'text-forest' : 'text-ink/35' }}">{{ $item->is_active ? 'Hiển thị' : 'Đang ẩn' }}</span>
                    </div>
                    <div class="mt-8">
                        <p class="text-xl font-semibold tracking-[-0.035em] text-ink">{{ $item->name }}</p>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-ink/55">{{ $item->short_description ?: 'Chưa có mô tả ngắn' }}</p>
                    </div>
                    <div class="mt-auto flex items-end justify-between gap-4 border-t border-ink/10 pt-5">
                        <p class="text-sm font-semibold tracking-[-0.02em] text-ink">{{ \App\Support\MoneyFormatter::format($item->base_price, $item->currency) }}</p>
                        <div class="flex gap-2">
                            <button type="button" data-product-dialog-open="edit-product-{{ $item->id }}" class="border border-ink/20 px-3 py-2 text-xs font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Sửa</button>
                            <form method="post" action="{{ route('admin.products.destroy', $item) }}" onsubmit="return confirm('Xóa sản phẩm này?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-2 py-2 text-xs font-semibold text-red-600 transition-colors hover:bg-red-50">Xóa</button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        @foreach($items as $item)
            @php($dialogId = 'edit-product-'.$item->id)
            <div id="{{ $dialogId }}" data-product-dialog @if((string) old('editing_product_id') !== (string) $item->id) hidden @endif class="fixed inset-0 z-[100] grid place-items-center bg-ink/65 p-4" role="dialog" aria-modal="true" aria-labelledby="{{ $dialogId }}-title">
                <div class="max-h-[calc(100vh-2rem)] w-full max-w-4xl overflow-y-auto bg-white p-6 shadow-[0_30px_80px_rgba(5,24,20,0.35)] md:p-9">
                    <div class="flex items-start justify-between gap-5 border-b border-ink/10 pb-6">
                        <div><p class="text-sm font-semibold text-forest">Chỉnh sửa trực tiếp</p><h2 id="{{ $dialogId }}-title" class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-ink">{{ $item->name }}</h2></div>
                        <button type="button" data-product-dialog-close="{{ $dialogId }}" class="grid size-10 place-items-center border border-ink/20 text-lg text-ink transition-colors hover:bg-ink hover:text-white" aria-label="Đóng">×</button>
                    </div>
                    <form method="post" enctype="multipart/form-data" action="{{ route('admin.products.update', $item) }}" class="mt-7 grid gap-5 md:grid-cols-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="editing_product_id" value="{{ $item->id }}">
                        @include('admin.products.fields', ['item' => $item, 'submitLabel' => 'Lưu sản phẩm', 'dialogId' => $dialogId])
                    </form>
                </div>
            </div>
        @endforeach
    </section>

    <div class="mt-8">{{ $items->onEachSide(1)->links() }}</div>

    @include('admin.products.inline-create')
@endsection

@extends('layouts.admin')

@section('title', $title)

@section('content')
    @php($createLabel = $route === 'pages' ? 'Thêm nội dung' : 'Thêm '.$title)

    <section class="grain ink-contrast relative overflow-hidden bg-ink px-6 py-10 text-white md:px-10 md:py-14">
        <div class="hero-wash absolute inset-0 opacity-60"></div>
        <div class="relative flex flex-col justify-between gap-8 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold text-coral">Không gian quản trị</p>
                <h1 class="mt-4 max-w-6xl text-4xl font-semibold tracking-[-0.055em] md:text-6xl">{{ $title }}</h1>
                <p class="mt-5 max-w-2xl text-sm leading-6 text-white/60">Tạo, cập nhật và xuất bản dữ liệu trực tiếp như khi bạn xây một trang nội dung.</p>
            </div>
            @if($canCreate ?? true)
                <a href="#create-{{ $route }}" class="accent-contrast shrink-0 rounded-full bg-coral px-6 py-3 text-sm font-semibold transition-transform hover:scale-105">{{ $createLabel }}</a>
            @endif
        </div>
    </section>

    <div class="mt-5 grid gap-3">
        @forelse($items as $item)
            <article data-motion-card class="group grid gap-5 bg-white p-6 shadow-[0_18px_50px_rgba(5,24,20,0.08)] transition-transform duration-500 hover:-translate-y-1 md:grid-cols-[minmax(0,1fr)_auto] md:items-center md:p-7">
                <div class="grid min-w-0 gap-5 sm:grid-cols-[repeat(auto-fit,minmax(10rem,1fr))]">
                    @foreach($columns as $key => $label)
                        <div class="min-w-0">
                            <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-ink/40">{{ $label }}</p>
                            @if($key === 'is_active')
                                <p class="mt-2 text-sm font-semibold {{ $item->$key ? 'text-forest' : 'text-ink/35' }}">{{ $item->$key ? 'Đang hiển thị' : 'Đang ẩn' }}</p>
                            @elseif($key === 'base_price')
                                <p class="mt-2 text-lg font-semibold tracking-[-0.03em] text-ink">{{ \App\Support\MoneyFormatter::format($item->$key, $item->currency) }}</p>
                            @else
                                <p class="mt-2 truncate text-sm font-semibold text-ink" title="{{ $item->$key }}">{{ Str::limit($item->$key, 70) }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div class="flex shrink-0 items-center gap-3 border-t border-ink/10 pt-4 md:border-l md:border-t-0 md:pl-6 md:pt-0">
                    <a href="{{ route('admin.'.$route.'.edit', $item) }}" class="rounded-full border border-ink/20 px-4 py-2 text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Chỉnh sửa</a>
                    @if($canDelete ?? true)
                        <form method="post" action="{{ route('admin.'.$route.'.destroy', $item) }}" onsubmit="return confirm('Xóa dữ liệu này?')">
                            @csrf
                            @method('DELETE')
                            <button class="rounded-full px-3 py-2 text-sm font-semibold text-red-600 transition-colors hover:bg-red-50">Xóa</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <div class="bg-white px-6 py-16 text-center shadow-[0_18px_50px_rgba(5,24,20,0.08)]">
                <p class="text-lg font-semibold text-ink">Chưa có dữ liệu.</p>
                @if($canCreate ?? true)
                    <a href="#create-{{ $route }}" class="accent-contrast mt-6 inline-flex rounded-full bg-coral px-6 py-3 text-sm font-semibold">{{ $createLabel }}</a>
                @endif
            </div>
        @endforelse
    </div>

    @if(method_exists($items, 'links'))
        <div class="mt-8">{{ $items->links() }}</div>
    @endif

    @if(($canCreate ?? true) && isset($createFields))
        @include('admin.crud.inline-create')
    @elseif(($canCreate ?? true) && isset($createPartial))
        @include($createPartial)
    @endif
@endsection

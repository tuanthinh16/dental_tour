@extends('layouts.admin')

@section('title', 'Tổng quan')

@section('content')
    <div class="relative overflow-hidden border border-ink/10 bg-white px-6 py-7 md:px-8">
        <div class="absolute inset-x-0 top-0 grid h-2 grid-cols-6">
            <span class="bg-forest"></span><span class="bg-coral"></span><span class="bg-amber-300"></span><span class="bg-sky-300"></span><span class="bg-violet-300"></span><span class="bg-rose-300"></span>
        </div>
        <div class="relative flex flex-col justify-between gap-6 pt-2 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold text-forest">Tổng quan vận hành</p>
                <h1 class="mt-2 max-w-6xl text-4xl font-semibold tracking-[-0.055em] text-ink md:text-5xl">Nội dung, hành trình và yêu cầu tư vấn.</h1>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-ink/55">Theo dõi dữ liệu đang hiển thị và xử lý các yêu cầu mới từ một nơi.</p>
            </div>
            <a href="{{ route('admin.consultations.index') }}" class="shrink-0 bg-coral px-5 py-3 text-sm font-semibold text-ink transition-transform hover:scale-105">Xem toàn bộ tư vấn</a>
        </div>
    </div>

    <section class="mt-6 grid grid-flow-dense gap-px bg-ink/10 md:grid-cols-12">
        @foreach([
            ['Tour', $tourCount, $activeTourCount.' đang hiển thị', 'md:col-span-5', 'bg-forest primary-contrast', 'text-white/65', 'text-mint'],
            ['Điểm đến', $destinationCount, $activeDestinationCount.' đang hiển thị', 'md:col-span-3', 'bg-coral accent-contrast', 'text-ink/65', 'text-ink/70'],
            ['Yêu cầu mới', $leadCounts['new'] ?? 0, 'Cần ưu tiên xử lý', 'md:col-span-4', 'bg-amber-200 text-amber-950', 'text-amber-950/65', 'text-amber-900'],
            ['Sản phẩm đi kèm', $productCount, $activeProductCount.' đang hiển thị', 'md:col-span-4', 'bg-sky-200 text-sky-950', 'text-sky-950/65', 'text-sky-800'],
            ['Dịch vụ', $serviceCount, 'Danh mục dùng chung', 'md:col-span-4', 'bg-violet-200 text-violet-950', 'text-violet-950/65', 'text-violet-800'],
            ['Trang nội dung', $activePageCount, $featuredTourCount.' tour nổi bật', 'md:col-span-4', 'bg-rose-200 text-rose-950', 'text-rose-950/65', 'text-rose-800'],
        ] as [$label, $value, $detail, $span, $cardClass, $labelClass, $detailClass])
            <div data-motion-card class="relative overflow-hidden p-6 md:p-7 {{ $span }} {{ $cardClass }}">
                <span class="absolute -right-5 -top-8 text-9xl font-semibold leading-none opacity-10" aria-hidden="true">{{ $value }}</span>
                <p class="relative text-sm font-medium {{ $labelClass }}">{{ $label }}</p>
                <p class="relative mt-3 text-5xl font-semibold tracking-[-0.055em]">{{ $value }}</p>
                <p class="relative mt-3 text-xs font-semibold {{ $detailClass }}">{{ $detail }}</p>
            </div>
        @endforeach
    </section>

    <section class="mt-6 grid gap-5 xl:grid-cols-[minmax(0,1.4fr)_minmax(19rem,0.6fr)]">
        <div class="overflow-hidden border border-ink/10 bg-white">
            <div class="flex items-center justify-between gap-5 bg-sky-100 px-6 py-5 md:px-7">
                <div><h2 class="text-xl font-semibold tracking-[-0.035em] text-ink">Yêu cầu tư vấn mới nhất</h2><p class="mt-1 text-sm text-ink/50">Theo dõi và phản hồi từ danh sách này.</p></div>
                <a href="{{ route('admin.consultations.index') }}" class="bg-ink px-4 py-2 text-sm font-semibold text-white transition-transform hover:scale-105">Xem tất cả</a>
            </div>
            <div>
                @forelse($latestConsultations as $consultation)
                    <a href="{{ route('admin.consultations.edit', $consultation) }}" class="group grid grid-cols-[minmax(0,1fr)_auto] items-center gap-5 border-b border-ink/10 px-6 py-5 last:border-b-0 transition-colors hover:bg-sky-50 md:px-7">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-ink">{{ $consultation->full_name }}</p>
                            <p class="mt-1 truncate text-xs text-ink/50">{{ $consultation->tour?->name ?: 'Chưa chọn tour' }} · {{ $consultation->phone }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-semibold {{ $consultation->status === 'new' ? 'text-coral' : 'text-forest' }}">{{ $leadStatusLabels[$consultation->status] }}</p>
                            <p class="mt-1 text-xs text-ink/40">{{ $consultation->created_at?->format('d/m/Y H:i') }}</p>
                        </div>
                    </a>
                @empty
                    <p class="px-6 py-14 text-center text-sm text-ink/50">Chưa có yêu cầu tư vấn.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-forest p-6 primary-contrast md:p-7">
            <h2 class="text-xl font-semibold tracking-[-0.035em]">Lối tắt quản trị</h2>
            <p class="mt-2 text-sm leading-6 text-white/60">Đi đến đúng khu vực để cập nhật dữ liệu.</p>
            <div class="mt-6 grid gap-px bg-white/15">
                @foreach([
                    ['admin.tours.index', 'Quản lý tour', 'Hành trình và giá bán', 'hover:bg-coral hover:text-ink'],
                    ['admin.products.index', 'Sản phẩm đi kèm', 'Vé, xe và tiện ích', 'hover:bg-sky-200 hover:text-sky-950'],
                    ['admin.destinations.index', 'Điểm đến', 'Vùng đất hiển thị trên landing', 'hover:bg-amber-200 hover:text-amber-950'],
                    ['admin.landing-editor', 'Visual Editor', 'Chỉnh nội dung trực tiếp trên landing', 'hover:bg-violet-200 hover:text-violet-950'],
                ] as [$route, $label, $description, $hoverClass])
                    <a href="{{ route($route) }}" class="group flex items-center justify-between gap-4 bg-forest px-4 py-4 text-white transition-colors {{ $hoverClass }}">
                        <span><span class="block text-sm font-semibold">{{ $label }}</span><span class="mt-1 block text-xs opacity-60 group-hover:opacity-75">{{ $description }}</span></span>
                        <span class="text-lg opacity-55 transition-transform group-hover:translate-x-1 group-hover:opacity-100">→</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mt-6 grid gap-px bg-ink/10 sm:grid-cols-2 lg:grid-cols-4">
        @foreach([
            ['new', 'bg-coral text-ink', 'text-ink/65'],
            ['contacted', 'bg-sky-200 text-sky-950', 'text-sky-800'],
            ['completed', 'bg-mint text-forest', 'text-forest'],
            ['cancelled', 'bg-ink text-white', 'text-white/65'],
        ] as [$status, $cardClass, $detailClass])
            <a href="{{ route('admin.consultations.index') }}" class="group p-5 transition-transform hover:-translate-y-1 {{ $cardClass }}">
                <p class="text-sm opacity-70">{{ $leadStatusLabels[$status] }}</p>
                <p class="mt-3 text-3xl font-semibold tracking-[-0.05em]">{{ $leadCounts[$status] ?? 0 }}</p>
                <p class="mt-3 text-xs font-semibold {{ $detailClass }}">Xem danh sách →</p>
            </a>
        @endforeach
    </section>
@endsection

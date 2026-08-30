@extends('layouts.admin')

@section('title', 'Yêu cầu tư vấn')

@section('content')
    <section class="grain ink-contrast relative overflow-hidden bg-ink px-6 py-10 text-white md:px-10 md:py-14">
        <div class="hero-wash absolute inset-0 opacity-60"></div>
        <div class="relative">
            <p class="text-sm font-semibold text-coral">Khách hàng</p>
            <h1 class="mt-4 max-w-6xl text-4xl font-semibold tracking-[-0.055em] md:text-6xl">Yêu cầu tư vấn</h1>
            <p class="mt-5 max-w-xl text-sm leading-6 text-white/60">Theo dõi và phản hồi những hành trình khách hàng đang mong muốn.</p>
        </div>
    </section>

    <div class="mt-5 grid gap-3">
        @forelse($items as $item)
            <article data-motion-card class="grid gap-5 bg-white p-6 shadow-[0_18px_50px_rgba(5,24,20,0.08)] md:grid-cols-[1.1fr_1fr_1fr_auto] md:items-center md:p-7">
                <div>
                    <p class="text-lg font-semibold tracking-[-0.03em] text-ink">{{ $item->full_name }}</p>
                    <p class="mt-2 text-sm text-ink/50">{{ $item->email }}<br>{{ $item->phone }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-ink/40">Tour quan tâm</p>
                    <p class="mt-2 text-sm font-semibold text-ink">{{ $item->tour?->name ?? 'Chưa chọn tour' }}</p>
                </div>
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.16em] text-ink/40">Trạng thái</p>
                    <p class="mt-2 text-sm font-semibold text-forest">{{ $item->status }}</p>
                    <p class="mt-1 text-xs text-ink/40">{{ $item->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <a href="{{ route('admin.consultations.edit', $item) }}" class="shrink-0 rounded-full border border-ink/20 px-4 py-2 text-center text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Xử lý</a>
            </article>
        @empty
            <div class="bg-white px-6 py-16 text-center text-ink/50 shadow-[0_18px_50px_rgba(5,24,20,0.08)]">Chưa có yêu cầu tư vấn.</div>
        @endforelse
    </div>
    <div class="mt-8">{{ $items->links() }}</div>
@endsection

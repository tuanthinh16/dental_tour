@extends('layouts.app')

@section('title', 'Tất cả tour | Dental Tour')

@section('content')
    <section class="grain ink-contrast relative flex min-h-[78vh] items-end overflow-hidden bg-ink px-5 pb-20 pt-40 text-white md:px-10 md:pb-28">
        <div class="absolute inset-0 bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://picsum.photos/seed/vietnam-travel-collection/1920/1080')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/65 to-ink/15"></div>
        <div class="relative mx-auto w-full max-w-[90rem]">
            <p class="text-sm font-medium text-coral">Bộ sưu tập hành trình</p>
            <h1 class="display-type mt-6 max-w-6xl font-semibold">Tìm chuyến đi vừa vặn với bạn.</h1>
            <p class="mt-8 max-w-2xl text-lg leading-8 text-white/60">Mỗi tour cân bằng giữa trải nghiệm có chiều sâu, thời gian tự do và dịch vụ được chăm chút.</p>
        </div>
    </section>

    <section class="bg-cream px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="grid gap-8 md:grid-cols-2 xl:grid-cols-3">
                @forelse($tours as $tour)
                    @include('tours._card', ['tour' => $tour])
                @empty
                    <p class="text-lg text-ink/55">Chưa có tour phù hợp.</p>
                @endforelse
            </div>
            <div class="mt-20">{{ $tours->links() }}</div>
        </div>
    </section>

    <section class="bg-coral px-5 py-28 text-center md:px-10 md:py-40">
        <div class="mx-auto max-w-5xl">
            <h2 class="chapter-title font-semibold">Chưa thấy hành trình phù hợp?</h2>
            <p class="mx-auto mt-7 max-w-xl text-lg leading-8 text-ink/65">Chúng tôi có thể thiết kế một tour riêng dựa trên thời gian, sở thích và nhịp điệu của bạn.</p>
            <a href="{{ route('home') }}#consultation" class="ink-contrast mt-10 inline-flex rounded-full bg-ink px-9 py-4 font-semibold transition-transform hover:scale-105">Trao đổi với chuyên gia</a>
        </div>
    </section>
@endsection

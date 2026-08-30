@extends('layouts.app')

@section('title', $tour->name.' | Dental Tour')
@section('meta_description', $tour->short_description)

@section('content')
    <section class="grain ink-contrast relative flex min-h-screen items-end overflow-hidden bg-ink px-5 pb-20 pt-40 text-white md:px-10 md:pb-28">
        <div class="absolute inset-0 bg-cover bg-center opacity-75" style="background-image: url('{{ $tour->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($tour->slug).'/1920/1080' }}')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/55 to-ink/10"></div>
        <div class="relative mx-auto w-full max-w-[90rem]">
            <p class="text-sm font-medium text-coral">{{ $tour->destination?->name }} / Tour trải nghiệm</p>
            <h1 class="display-type mt-6 max-w-6xl font-semibold">{{ $tour->name }}</h1>
            <div class="mt-10 grid gap-8 border-t border-white/25 pt-8 md:grid-cols-[1fr_auto_auto] md:items-end">
                <p class="max-w-2xl text-lg leading-8 text-white/65">{{ $tour->short_description }}</p>
                <div>
                    <p class="text-xs text-white/45">Thời lượng</p>
                    <p class="mt-2 text-xl font-semibold">{{ $tour->duration_days }} ngày{{ $tour->duration_nights ? ', '.$tour->duration_nights.' đêm' : '' }}</p>
                </div>
                <div>
                    <p class="text-xs text-white/45">Giá từ</p>
                    <p class="mt-2 text-3xl font-semibold text-coral">{{ \App\Support\MoneyFormatter::format($tour->base_price, $tour->currency) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-cream px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto grid max-w-[90rem] gap-16 lg:grid-cols-[0.7fr_1.3fr]">
            <h2 class="chapter-title font-semibold lg:sticky lg:top-36 lg:self-start">Một hành trình để sống chậm hơn.</h2>
            <div>
                <div class="whitespace-pre-line text-xl leading-9 text-ink/62">{{ $tour->description }}</div>
                <a href="#consultation" class="mt-10 inline-flex border-b border-ink pb-2 text-sm font-semibold">Nhận lịch trình tư vấn</a>
            </div>
        </div>
    </section>

    <section class="ink-contrast bg-ink px-5 py-32 text-white md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="grid gap-14 lg:grid-cols-[0.65fr_1.35fr]">
                <div class="lg:sticky lg:top-36 lg:self-start">
                    <p class="text-sm font-medium text-coral">Lịch trình</p>
                    <h2 class="chapter-title mt-6 font-semibold">Mỗi ngày một nhịp khám phá.</h2>
                </div>
                <div class="divide-y divide-white/15 border-t border-white/15">
                    @foreach($tour->itineraries->where('is_active', true) as $day)
                        <article data-motion-card class="grid gap-7 py-10 md:grid-cols-[7rem_1fr] md:py-14">
                            <p class="text-sm text-coral">Ngày {{ str_pad($day->day_number, 2, '0', STR_PAD_LEFT) }}</p>
                            <div>
                                <h3 class="text-3xl font-semibold tracking-[-0.04em]">{{ $day->title }}</h3>
                                <p class="mt-5 max-w-2xl text-base leading-8 text-white/55">{{ $day->description }}</p>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="bg-mint px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto grid max-w-[90rem] gap-6 md:grid-cols-2">
            <div class="bg-white p-8 md:p-12">
                <h2 class="text-3xl font-semibold tracking-[-0.04em]">Đã bao gồm</h2>
                <ul class="mt-10 divide-y divide-ink/10">
                    @foreach($tour->services->where('is_active', true) as $service)
                        <li class="flex items-center gap-4 py-5 text-base"><span class="size-2 rounded-full bg-coral"></span>{{ $service->name }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-sand p-8 md:p-12">
                <h2 class="text-3xl font-semibold tracking-[-0.04em]">Chưa bao gồm</h2>
                <ul class="mt-10 divide-y divide-ink/10">
                    @foreach($tour->excludedItems->where('is_active', true) as $item)
                        <li class="flex items-center gap-4 py-5 text-base text-ink/60"><span class="size-2 rounded-full border border-ink/45"></span>{{ $item->content }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </section>

    <section class="bg-coral px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto grid max-w-[90rem] gap-16 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <h2 class="chapter-title max-w-xl font-semibold">Biến hành trình này thành của riêng bạn.</h2>
                <p class="mt-8 max-w-md text-lg leading-8 text-ink/65">Ngày đi, số người và nhịp trải nghiệm đều có thể điều chỉnh theo mong muốn.</p>
            </div>
            @include('tours._consultation', ['selectedTour' => $tour, 'allTours' => collect([$tour])])
        </div>
    </section>
@endsection

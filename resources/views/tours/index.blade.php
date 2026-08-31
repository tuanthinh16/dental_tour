@extends('layouts.app')

@section('title', __('site.footer.all_tours').' | Dental Tour')

@section('content')
    <section class="grain ink-contrast relative flex min-h-[78vh] items-end overflow-hidden bg-ink px-5 pb-20 pt-40 text-white md:px-10 md:pb-28">
        <div class="absolute inset-0 bg-cover bg-center opacity-40 grayscale" style="background-image: url('https://picsum.photos/seed/vietnam-travel-collection/1920/1080')"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/65 to-ink/15"></div>
        <div class="relative mx-auto w-full max-w-[90rem]">
            <p class="text-sm font-medium text-coral">{{ __('site.tour.collection') }}</p>
            <h1 class="display-type mt-6 max-w-6xl font-semibold">{{ __('site.tour.collection_title') }}</h1>
            <p class="mt-8 max-w-2xl text-lg leading-8 text-white/60">{{ __('site.tour.collection_description') }}</p>
        </div>
    </section>

    <section class="bg-cream px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="flex flex-col gap-6 md:flex-row md:items-end md:justify-between">
                <div>
                    <p class="text-sm font-medium text-forest">Khám phá theo nhịp của bạn</p>
                    <h2 class="mt-3 text-3xl font-semibold tracking-[-0.04em] text-ink md:text-4xl">Lướt qua từng trải nghiệm.</h2>
                </div>
                <div data-tour-rail-controls class="flex gap-2">
                    <button data-tour-rail-prev type="button" title="Xem tour trước" aria-label="Tour trước" class="grid size-11 place-items-center border border-ink/25 text-lg transition-colors hover:bg-ink hover:text-white">←</button>
                    <button data-tour-rail-next type="button" title="Xem tour tiếp theo" aria-label="Tour tiếp theo" class="grid size-11 place-items-center border border-ink/25 text-lg transition-colors hover:bg-ink hover:text-white">→</button>
                </div>
            </div>
            <div data-tour-rail tabindex="0" class="tour-rail mt-12 flex snap-x snap-mandatory gap-8 overflow-x-auto pr-5 pb-5 md:mt-16 md:-mr-10 md:gap-12 md:pr-10">
                @forelse($tours as $tour)
                    <div class="w-[calc(100vw-3rem)] shrink-0 snap-start sm:w-[42rem] lg:w-[78vw] lg:max-w-[76rem]">
                        @include('tours._card', ['tour' => $tour])
                    </div>
                @empty
                    <p class="text-lg text-ink/55">{{ __('site.tour.no_results') }}</p>
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

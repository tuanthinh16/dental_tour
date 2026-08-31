@extends('layouts.app')

@section('title', $settings['seo_site_title'] ?? 'Dental Tour | Việt Nam theo nhịp riêng')

@section('content')
    @php($editorMode = $editorMode ?? false)
    @php($heroImage = $settings['landing_hero_image'] ?? 'https://picsum.photos/seed/vietnam-coast-journey/1920/1080')
    @php($heroEyebrow = $settings['landing_hero_eyebrow'] ?? 'Những hành trình có chiều sâu')
    @php($heroTitleLine1 = $settings['landing_hero_title_line_1'] ?? 'Việt Nam rộng mở.')
    @php($heroTitleBeforeImage = $settings['landing_hero_title_before_image'] ?? 'Bạn đi theo')
    @php($heroTitleAfterImage = $settings['landing_hero_title_after_image'] ?? 'nhịp riêng.')
    @php($heroDescription = $settings['landing_hero_description'] ?? 'Từ bờ biển miền Trung đến những thị trấn di sản, mỗi chuyến đi được thiết kế để bạn thật sự sống trong điểm đến.')

    @if($editorMode)
        <div class="fixed inset-x-0 bottom-5 z-[90] flex justify-center px-4">
            <div class="flex items-center gap-2 rounded-full bg-white p-2 text-ink shadow-[0_20px_70px_rgba(5,24,20,0.3)]">
                <span class="hidden px-3 text-xs font-semibold sm:block">Visual Editor đang bật</span>
                <a href="{{ route('admin.dashboard') }}" class="rounded-full border border-ink/15 px-4 py-2 text-xs font-semibold transition-colors hover:bg-ink hover:text-white">Về CMS</a>
                <a href="{{ route('home') }}" target="_blank" rel="noopener" class="rounded-full bg-ink px-4 py-2 text-xs font-semibold text-white">Xem website</a>
            </div>
        </div>
    @endif

    <section id="landing-hero" class="group/hero grain ink-contrast relative flex min-h-screen items-center justify-center overflow-hidden bg-ink text-white">
        <div class="absolute inset-0 scale-105 bg-cover bg-center opacity-80" style="background-image: url('{{ $heroImage }}')"></div>
        <div class="hero-wash absolute inset-0"></div>
        @if($editorMode)
            <div class="pointer-events-none absolute inset-0 z-30 grid place-items-center bg-ink/20 opacity-0 transition-opacity duration-300 group-hover/hero:opacity-100">
                <div class="pointer-events-auto flex flex-wrap justify-center gap-3">
                    <button data-visual-editor-open="edit-hero" type="button" class="accent-contrast rounded-full bg-coral px-5 py-3 text-sm font-semibold shadow-xl">Sửa hero</button>
                </div>
            </div>
            @include('admin.landing-editor.hero-dialog')
        @endif
        <div class="relative mx-auto flex w-full max-w-[100rem] flex-col items-center px-6 pb-20 pt-36 text-center md:px-10 md:pt-44">
            <div class="overflow-hidden">
                <p data-hero-line class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">{{ $heroEyebrow }}</p>
            </div>
            <h1 class="display-type mt-8 max-w-6xl font-semibold">
                <span class="block overflow-hidden"><span data-hero-line class="block">{{ $heroTitleLine1 }}</span></span>
                <span class="block overflow-hidden"><span data-hero-line class="block">{{ $heroTitleBeforeImage }} <span class="inline-journey-image" style="background-image: url('https://picsum.photos/seed/hoi-an-lantern/480/240')"></span> {{ $heroTitleAfterImage }}</span></span>
            </h1>
            <p data-hero-action class="mt-8 max-w-2xl text-base leading-7 text-white/72 md:text-xl md:leading-8">{{ $heroDescription }}</p>
            <div data-hero-action class="mt-10 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('tours.index') }}" title="Khám phá danh sách hành trình" class="accent-contrast rounded-full bg-coral px-8 py-4 font-semibold transition-transform hover:scale-105">Khám phá hành trình</a>
                <a href="#consultation" title="Thiết kế tour riêng với chuyên gia" class="rounded-full border border-white/45 bg-white/10 px-8 py-4 font-semibold text-white backdrop-blur transition-colors hover:bg-white hover:text-ink">Thiết kế tour riêng</a>
            </div>
        </div>
        <div class="absolute bottom-8 left-1/2 h-12 w-px -translate-x-1/2 bg-gradient-to-b from-white/70 to-transparent"></div>
    </section>

    <section id="destinations" class="bg-cream px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="grid gap-8 lg:grid-cols-[0.8fr_1.2fr] lg:items-end">
                <h2 class="chapter-title max-w-4xl font-semibold text-ink">Nơi ký ức bắt đầu từ một vùng đất.</h2>
                <p class="max-w-xl text-lg leading-8 text-ink/58 lg:justify-self-end">Mỗi điểm đến được chọn vì có một nhịp sống riêng. Chúng tôi đưa bạn đến gần hơn với cảnh quan, con người và những câu chuyện nằm ngoài lối mòn.</p>
            </div>

            @php($featuredDestinations = $destinations->take(3)->values())
            <div class="mt-16 grid grid-flow-dense auto-rows-[16rem] grid-cols-1 gap-3 md:grid-cols-12 md:auto-rows-[18rem]">
                @foreach($featuredDestinations as $index => $destination)
                    <article data-motion-card class="group relative overflow-hidden bg-forest {{ $index === 0 ? 'md:col-span-7 md:row-span-2' : 'md:col-span-5 md:row-span-1' }}">
                        <div class="absolute inset-0 bg-cover bg-center grayscale-[20%] transition-transform duration-700 ease-out group-hover:scale-105" style="background-image: url('{{ $destination->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($destination->slug).'/1200/900' }}')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/10 to-transparent"></div>
                        <a href="{{ route('tours.index') }}" class="absolute inset-0 z-10 {{ $editorMode ? 'pointer-events-none' : '' }}" aria-label="Xem tour tại {{ $destination->name }}"></a>
                        <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-5 p-7 text-white md:p-9">
                            <div>
                                <h3 class="text-3xl font-semibold tracking-[-0.04em] md:text-4xl">{{ $destination->name }}</h3>
                                <p class="mt-2 text-sm text-white/65">{{ $destination->tours_count ?? 0 }} hành trình chọn lọc</p>
                            </div>
                            <span class="text-sm font-medium">Xem tour</span>
                        </div>
                        @if($editorMode)
                            <div class="absolute right-4 top-4 z-30 opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                <button data-visual-editor-open="edit-destination-{{ $destination->id }}" type="button" class="accent-contrast rounded-full bg-coral px-4 py-2 text-xs font-semibold shadow-xl">Sửa</button>
                            </div>
                            @include('admin.landing-editor.destination-dialog', ['destination' => $destination])
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="featured-tours" class="ink-contrast bg-ink px-5 py-32 text-white md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="grid gap-8 lg:grid-cols-[1.15fr_0.85fr] lg:items-end">
                <div>
                    <p class="text-sm font-medium text-coral">Hành trình được yêu thích</p>
                    <h2 class="chapter-title mt-5 max-w-4xl font-semibold">Đi ít hơn. Cảm nhận nhiều hơn.</h2>
                </div>
                <div class="lg:justify-self-end">
                    <p class="max-w-lg text-base leading-7 text-white/55">Những trải nghiệm được sắp theo một nhịp thoáng hơn để bạn dễ so sánh, chọn lựa và bắt đầu hành trình.</p>
                    <div data-tour-rail-controls class="mt-7 flex items-center gap-5">
                        <a href="{{ route('tours.index') }}" class="inline-flex border-b border-white pb-2 text-sm font-semibold text-white transition-colors hover:border-coral hover:text-coral">Xem toàn bộ tour</a>
                        <div class="flex gap-2">
                            <button data-tour-rail-prev type="button" title="Xem tour trước" aria-label="Tour trước" class="grid size-10 place-items-center border border-white/25 text-lg transition-colors hover:bg-white hover:text-ink">←</button>
                            <button data-tour-rail-next type="button" title="Xem tour tiếp theo" aria-label="Tour tiếp theo" class="grid size-10 place-items-center border border-white/25 text-lg transition-colors hover:bg-white hover:text-ink">→</button>
                        </div>
                    </div>
                </div>
            </div>

            <div data-tour-rail tabindex="0" class="tour-rail mt-16 flex snap-x snap-mandatory gap-8 overflow-x-auto pr-5 pb-5 md:mt-20 md:-mr-10 md:gap-12 md:pr-10">
                @foreach($featuredTours as $tour)
                    <div class="w-[calc(100vw-3rem)] shrink-0 snap-start sm:w-[42rem] lg:w-[78vw] lg:max-w-[76rem]">
                        @include('tours._card', ['tour' => $tour, 'editorMode' => $editorMode, 'tone' => 'dark'])
                    </div>
                @endforeach
                @if($editorMode)
                    <div class="w-[calc(100vw-3rem)] shrink-0 snap-start sm:w-[42rem] lg:w-[78vw] lg:max-w-[76rem]">
                        @include('admin.landing-editor.tour-create')
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="bg-mint px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <p class="max-w-xl text-sm font-medium text-forest">Không chỉ là một chuyến đi</p>
            <p data-reveal-copy class="mt-8 max-w-7xl text-4xl font-medium leading-[1.08] tracking-[-0.045em] text-ink md:text-6xl lg:text-7xl">
                @foreach(explode(' ', 'Chúng tôi tạo khoảng trống để bạn chậm lại, lắng nghe vùng đất và mang về những ký ức có thể sống cùng mình thật lâu.') as $word)
                    <span class="reveal-word inline-block">{{ $word }}</span>
                @endforeach
            </p>
        </div>
    </section>

    <section id="destination-priority" class="bg-cream px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
                <h2 class="chapter-title max-w-4xl font-semibold">Năm sắc thái.<br>Một Việt Nam.</h2>
                <p class="max-w-sm text-base leading-7 text-ink/55">Chạm hoặc rê chuột để mở rộng từng điểm đến và tìm nhịp điệu phù hợp với bạn.</p>
            </div>
            @if($editorMode)
                <div class="mt-8 flex items-center gap-3 text-sm text-ink/55">
                    <span class="grid size-8 place-items-center border border-ink/15 bg-white" aria-hidden="true">↕</span>
                    Kéo các block bên dưới để thay đổi độ ưu tiên hiển thị.
                    <span data-destination-sort-status class="font-semibold text-forest"></span>
                </div>
                <form data-destination-sort-form action="{{ route('admin.landing-editor.destinations.reorder') }}" class="hidden">@csrf @method('PUT')</form>
            @endif
            <div data-destination-sort-list class="destination-accordion mt-16 flex h-[38rem] overflow-hidden border border-ink/15 bg-ink">
                @foreach($destinations as $destination)
                    <article data-destination-sort-item="{{ $destination->id }}" @if($editorMode) draggable="true" @endif class="destination-panel group relative min-w-0 overflow-hidden border-r border-white/15 last:border-r-0 {{ $editorMode ? 'cursor-grab active:cursor-grabbing' : '' }}">
                        <div class="absolute inset-0 bg-cover bg-center opacity-75 grayscale transition duration-700 group-hover:scale-105 group-hover:grayscale-0" style="background-image: url('{{ $destination->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($destination->slug).'/1000/1200' }}')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/10 to-transparent"></div>
                        <a href="{{ route('tours.index') }}" class="absolute inset-0 z-10 {{ $editorMode ? 'pointer-events-none' : '' }}" aria-label="Xem tour tại {{ $destination->name }}"></a>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white md:p-8">
                            <h3 class="text-2xl font-semibold tracking-[-0.04em] [writing-mode:horizontal-tb]">{{ $destination->name }}</h3>
                            <p class="mt-3 max-h-0 overflow-hidden text-sm leading-6 text-white/65 opacity-0 transition-all duration-500 group-hover:max-h-24 group-hover:opacity-100">{{ $destination->short_description }}</p>
                        </div>
                        @if($editorMode)
                            <button data-visual-editor-open="edit-destination-accordion-{{ $destination->id }}" type="button" class="accent-contrast absolute right-4 top-4 z-30 rounded-full bg-coral px-4 py-2 text-xs font-semibold opacity-0 shadow-xl transition-opacity group-hover:opacity-100">Sửa</button>
                            @include('admin.landing-editor.destination-dialog', ['destination' => $destination, 'panelId' => 'edit-destination-accordion-'.$destination->id])
                        @endif
                    </article>
                @endforeach
            </div>
            @if($editorMode)
                @include('admin.landing-editor.destination-create')
            @endif
        </div>
    </section>

    <section class="bg-sand px-5 py-20 md:px-10 md:py-28">
        <div class="mx-auto max-w-[90rem]">
            <div class="flex flex-col justify-between gap-6 border-b border-ink/15 pb-7 md:flex-row md:items-end">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-forest">Khách hàng chia sẻ</p>
                    <h2 class="mt-3 max-w-3xl text-3xl font-semibold leading-[1.05] tracking-[-0.045em] md:text-4xl">Đánh giá từ người đã trải nghiệm.</h2>
                    <p class="mt-4 max-w-xl text-sm leading-6 text-ink/55">Những chia sẻ được gửi lại sau khi hành trình kết thúc.</p>
                </div>
                <div class="flex items-center gap-3 md:pb-1">
                    <span class="text-4xl font-semibold tracking-[-0.05em]">4.9</span>
                    <div>
                        <div class="flex gap-1 text-coral" aria-label="4.9 trên 5 sao">
                            @for($star = 0; $star < 5; $star++)
                                <svg viewBox="0 0 20 20" class="size-4 fill-current" aria-hidden="true"><path d="m10 1.8 2.45 4.97 5.48.8-3.97 3.86.94 5.46L10 14.3l-4.9 2.58.94-5.46-3.97-3.86 5.48-.8L10 1.8Z" /></svg>
                            @endfor
                        </div>
                        <p class="mt-1 text-xs font-medium text-ink/45">Điểm hài lòng trung bình</p>
                    </div>
                </div>
            </div>

            <div data-review-list class="mt-9 grid overflow-hidden border border-ink/15 md:grid-cols-3">
                @foreach([
                    ['Mia Laurent', 'France', 'mia-laurent-vietnam-review', 'Tôi không cảm thấy mình đang theo một tour. Tôi cảm thấy mình đang được sống ở Việt Nam.'],
                    ['Daniel Kim', 'Singapore', 'daniel-kim-family-review', 'Lịch trình đủ tinh tế để cả gia đình có thời gian ở bên nhau, không phải chạy theo điểm đến.'],
                    ['Sofia Rossi', 'Italy', 'sofia-rossi-travel-review', 'Những điều đáng nhớ nhất lại là các khoảnh khắc nhỏ mà đội ngũ đã âm thầm chuẩn bị.'],
                ] as [$name, $country, $avatar, $quote])
                    <article data-review-item class="flex min-h-64 flex-col border-b border-ink/15 bg-sand p-6 last:border-b-0 md:min-h-72 md:border-b-0 md:border-r md:p-7 md:last:border-r-0">
                        <div class="flex gap-1 text-coral" aria-label="5 trên 5 sao">
                            @for($star = 0; $star < 5; $star++)
                                <svg viewBox="0 0 20 20" class="size-4 fill-current" aria-hidden="true"><path d="m10 1.8 2.45 4.97 5.48.8-3.97 3.86.94 5.46L10 14.3l-4.9 2.58.94-5.46-3.97-3.86 5.48-.8L10 1.8Z" /></svg>
                            @endfor
                        </div>
                        <blockquote class="mt-5 text-lg font-medium leading-7 tracking-[-0.025em]">“{{ $quote }}”</blockquote>
                        <div class="mt-auto flex items-center gap-3 border-t border-ink/10 pt-5">
                            <img src="https://picsum.photos/seed/{{ $avatar }}/120/120" alt="Ảnh của {{ $name }}" width="36" height="36" loading="lazy" decoding="async" class="size-9 rounded-full object-cover grayscale-[20%]">
                            <div>
                                <p class="text-sm font-semibold">{{ $name }}, {{ $country }}</p>
                                <p class="mt-0.5 text-xs text-ink/45">Khách đã trải nghiệm</p>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-coral px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto grid max-w-[90rem] gap-16 lg:grid-cols-[0.8fr_1.2fr]">
            <div>
                <h2 class="chapter-title max-w-xl font-semibold text-ink">Kể chúng tôi về chuyến đi bạn mong muốn.</h2>
                <p class="mt-8 max-w-md text-lg leading-8 text-ink/65">Một chuyên gia hành trình sẽ liên hệ, lắng nghe và cùng bạn định hình lịch trình phù hợp.</p>
            </div>
            @include('tours._consultation', ['selectedTour' => null, 'allTours' => $tours])
        </div>
    </section>
@endsection

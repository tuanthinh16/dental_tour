@extends('layouts.app')

@section('title', 'Dental Tour | Việt Nam theo nhịp riêng')

@section('content')
    <section class="grain ink-contrast relative flex min-h-screen items-center justify-center overflow-hidden bg-ink text-white">
        <div class="absolute inset-0 scale-105 bg-cover bg-center opacity-80" style="background-image: url('https://picsum.photos/seed/vietnam-coast-journey/1920/1080')"></div>
        <div class="hero-wash absolute inset-0"></div>
        <div class="relative mx-auto flex w-full max-w-[100rem] flex-col items-center px-6 pb-20 pt-36 text-center md:px-10 md:pt-44">
            <div class="overflow-hidden">
                <p data-hero-line class="text-xs font-semibold uppercase tracking-[0.28em] text-white/70">Những hành trình có chiều sâu</p>
            </div>
            <h1 class="display-type mt-8 max-w-6xl font-semibold">
                <span class="block overflow-hidden"><span data-hero-line class="block">Việt Nam rộng mở.</span></span>
                <span class="block overflow-hidden"><span data-hero-line class="block">Bạn đi theo <span class="inline-journey-image" style="background-image: url('https://picsum.photos/seed/hoi-an-lantern/480/240')"></span> nhịp riêng.</span></span>
            </h1>
            <p data-hero-action class="mt-8 max-w-2xl text-base leading-7 text-white/72 md:text-xl md:leading-8">Từ bờ biển miền Trung đến những thị trấn di sản, mỗi chuyến đi được thiết kế để bạn thật sự sống trong điểm đến.</p>
            <div data-hero-action class="mt-10 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('tours.index') }}" class="accent-contrast rounded-full bg-coral px-8 py-4 font-semibold transition-transform hover:scale-105">Khám phá hành trình</a>
                <a href="#consultation" class="rounded-full border border-white/45 bg-white/10 px-8 py-4 font-semibold text-white backdrop-blur transition-colors hover:bg-white hover:text-ink">Thiết kế tour riêng</a>
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
                    <a href="{{ route('tours.index') }}" data-motion-card class="group relative overflow-hidden bg-forest {{ $index === 0 ? 'md:col-span-7 md:row-span-2' : 'md:col-span-5 md:row-span-1' }}">
                        <div class="absolute inset-0 bg-cover bg-center grayscale-[20%] transition-transform duration-700 ease-out group-hover:scale-105" style="background-image: url('{{ $destination->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($destination->slug).'/1200/900' }}')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink/90 via-ink/10 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-5 p-7 text-white md:p-9">
                            <div>
                                <h3 class="text-3xl font-semibold tracking-[-0.04em] md:text-4xl">{{ $destination->name }}</h3>
                                <p class="mt-2 text-sm text-white/65">{{ $destination->tours_count ?? 0 }} hành trình chọn lọc</p>
                            </div>
                            <span class="text-sm font-medium">Xem tour</span>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="ink-contrast bg-ink px-5 py-32 text-white md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="grid gap-10 lg:grid-cols-[0.75fr_1.25fr]">
                <div class="lg:sticky lg:top-36 lg:self-start">
                    <p class="text-sm font-medium text-coral">Hành trình được yêu thích</p>
                    <h2 class="chapter-title mt-5 max-w-xl font-semibold">Đi ít hơn. Cảm nhận nhiều hơn.</h2>
                    <a href="{{ route('tours.index') }}" class="mt-10 inline-flex border-b border-white pb-2 text-sm font-semibold text-white transition-colors hover:border-coral hover:text-coral">Xem toàn bộ tour</a>
                </div>
                <div class="space-y-8 pb-24">
                    @foreach($featuredTours as $index => $tour)
                        <div class="tour-stack-card" style="--stack-offset: {{ 7 + ($index * 1.15) }}rem">
                            @include('tours._card', ['tour' => $tour, 'stacked' => true])
                        </div>
                    @endforeach
                </div>
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

    <section class="bg-cream px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="flex flex-col justify-between gap-8 md:flex-row md:items-end">
                <h2 class="chapter-title max-w-4xl font-semibold">Năm sắc thái.<br>Một Việt Nam.</h2>
                <p class="max-w-sm text-base leading-7 text-ink/55">Chạm hoặc rê chuột để mở rộng từng điểm đến và tìm nhịp điệu phù hợp với bạn.</p>
            </div>
            <div class="destination-accordion mt-16 flex h-[38rem] overflow-hidden border border-ink/15 bg-ink">
                @foreach($destinations as $destination)
                    <a href="{{ route('tours.index') }}" class="destination-panel group relative min-w-0 overflow-hidden border-r border-white/15 last:border-r-0">
                        <div class="absolute inset-0 bg-cover bg-center opacity-75 grayscale transition duration-700 group-hover:scale-105 group-hover:grayscale-0" style="background-image: url('{{ $destination->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($destination->slug).'/1000/1200' }}')"></div>
                        <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/10 to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 text-white md:p-8">
                            <h3 class="text-2xl font-semibold tracking-[-0.04em] [writing-mode:horizontal-tb]">{{ $destination->name }}</h3>
                            <p class="mt-3 max-h-0 overflow-hidden text-sm leading-6 text-white/65 opacity-0 transition-all duration-500 group-hover:max-h-24 group-hover:opacity-100">{{ $destination->short_description }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-sand px-5 py-32 md:px-10 md:py-48">
        <div class="mx-auto max-w-[90rem]">
            <div class="flex flex-col justify-between gap-8 border-b border-ink/15 pb-10 md:flex-row md:items-end">
                <div>
                    <h2 class="chapter-title max-w-4xl font-semibold">Đánh giá từ người đã trải nghiệm.</h2>
                    <p class="mt-6 max-w-xl text-base leading-7 text-ink/55">Những chia sẻ được gửi lại sau khi hành trình kết thúc.</p>
                </div>
                <div class="flex items-center gap-4">
                    <span class="text-5xl font-semibold tracking-[-0.05em]">4.9</span>
                    <div>
                        <div class="flex gap-1 text-coral" aria-label="4.9 trên 5 sao">
                            @for($star = 0; $star < 5; $star++)
                                <svg viewBox="0 0 20 20" class="size-4 fill-current" aria-hidden="true"><path d="m10 1.8 2.45 4.97 5.48.8-3.97 3.86.94 5.46L10 14.3l-4.9 2.58.94-5.46-3.97-3.86 5.48-.8L10 1.8Z" /></svg>
                            @endfor
                        </div>
                        <p class="mt-2 text-xs font-medium text-ink/45">Điểm hài lòng trung bình</p>
                    </div>
                </div>
            </div>

            <div class="mt-14 grid gap-12 lg:grid-cols-[0.72fr_1.28fr] lg:items-center">
                <div data-review-image class="relative aspect-[4/5] max-h-[42rem] overflow-hidden">
                    <img src="https://picsum.photos/seed/vietnam-traveler-portrait/1000/1250" alt="Du khách khám phá Việt Nam" class="h-full w-full object-cover grayscale-[35%] contrast-125 transition-transform duration-700 ease-out hover:scale-105">
                    <div class="absolute inset-0 bg-forest/15 mix-blend-multiply"></div>
                </div>
                <div>
                    @foreach([
                        ['Mia Laurent', 'France', 'mia-laurent-vietnam-review', 'Tôi không cảm thấy mình đang theo một tour. Tôi cảm thấy mình đang được sống ở Việt Nam.'],
                        ['Daniel Kim', 'Singapore', 'daniel-kim-family-review', 'Lịch trình đủ tinh tế để cả gia đình có thời gian ở bên nhau, không phải chạy theo điểm đến.'],
                        ['Sofia Rossi', 'Italy', 'sofia-rossi-travel-review', 'Những điều đáng nhớ nhất lại là các khoảnh khắc nhỏ mà đội ngũ đã âm thầm chuẩn bị.'],
                    ] as $index => [$name, $country, $avatar, $quote])
                        <div data-testimonial @class(['hidden' => $index > 0])>
                            <div class="flex gap-1 text-coral" aria-label="5 trên 5 sao">
                                @for($star = 0; $star < 5; $star++)
                                    <svg viewBox="0 0 20 20" class="size-4 fill-current" aria-hidden="true"><path d="m10 1.8 2.45 4.97 5.48.8-3.97 3.86.94 5.46L10 14.3l-4.9 2.58.94-5.46-3.97-3.86 5.48-.8L10 1.8Z" /></svg>
                                @endfor
                            </div>
                            <blockquote class="mt-6 text-4xl font-medium leading-[1.08] tracking-[-0.045em] md:text-6xl">“{{ $quote }}”</blockquote>
                            <div class="mt-9 flex items-center gap-4">
                                <img src="https://picsum.photos/seed/{{ $avatar }}/120/120" alt="Ảnh của {{ $name }}" class="size-12 rounded-full object-cover grayscale-[20%]">
                                <div>
                                    <p class="text-sm font-semibold">{{ $name }}, {{ $country }}</p>
                                    <p class="mt-1 text-xs text-ink/45">Khách đã trải nghiệm</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-12 flex gap-3">
                        <button type="button" data-testimonial-prev aria-label="Đánh giá trước" class="grid size-12 place-items-center rounded-full border border-ink/25 text-lg transition-colors hover:bg-ink hover:text-white">‹</button>
                        <button type="button" data-testimonial-next aria-label="Đánh giá tiếp theo" class="grid size-12 place-items-center rounded-full bg-ink text-lg text-white transition-transform hover:scale-105">›</button>
                    </div>
                </div>
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

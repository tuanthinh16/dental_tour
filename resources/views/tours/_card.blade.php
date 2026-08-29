@php($isStacked = $stacked ?? false)
@php($includedServices = $tour->services->where('is_active', true)->take(5))

<article @class([
    'group overflow-hidden bg-white text-ink shadow-[0_30px_80px_rgba(5,24,20,0.18)]',
    'grid min-h-[30rem] md:grid-cols-[1.1fr_0.9fr]' => $isStacked,
    'h-full' => !$isStacked,
]) @if(!$isStacked) data-motion-card @endif>
    <a href="{{ route('tours.show', $tour->slug) }}" class="contents">
        <div @class([
            'relative overflow-hidden bg-sand',
            'min-h-[22rem] md:min-h-full' => $isStacked,
            'aspect-[4/3]' => !$isStacked,
        ])>
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-105" style="background-image: url('{{ $tour->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($tour->slug).'/1200/900' }}')"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-ink/45 to-transparent"></div>
            @if($includedServices->isNotEmpty())
                <div class="absolute inset-0 hidden flex-col justify-end bg-ink/90 p-7 text-white opacity-0 backdrop-blur-sm transition-all duration-500 group-hover:opacity-100 group-focus-within:opacity-100 md:flex md:p-9">
                    <p class="text-sm font-semibold text-coral">Dịch vụ trong gói</p>
                    <ul class="mt-5 grid gap-3 sm:grid-cols-2">
                        @foreach($includedServices as $service)
                            <li class="flex items-center gap-3 text-sm text-white/80">
                                <span class="grid size-5 shrink-0 place-items-center rounded-full bg-white/12" aria-hidden="true">
                                    <svg viewBox="0 0 20 20" class="size-3 fill-none stroke-current" stroke-width="2"><path d="m5 10 3 3 7-7" /></svg>
                                </span>
                                {{ $service->name }}
                            </li>
                        @endforeach
                    </ul>
                    <p class="mt-6 text-xs text-white/45">Chọn để xem toàn bộ quyền lợi</p>
                </div>
            @endif
            @if($tour->badge)
                <span class="accent-contrast absolute left-5 top-5 bg-coral px-3 py-2 text-xs font-semibold">{{ $tour->badge }}</span>
            @endif
        </div>
        <div @class(['flex flex-col justify-between p-7 md:p-10', 'min-h-[20rem]' => !$isStacked])>
            <div>
                <p class="text-sm font-medium text-forest">{{ $tour->destination?->name }} / {{ $tour->duration_days }} ngày</p>
                <h3 @class(['mt-5 font-semibold tracking-[-0.045em]', 'text-4xl md:text-5xl' => $isStacked, 'text-3xl' => !$isStacked])>{{ $tour->name }}</h3>
                <p class="mt-5 line-clamp-3 max-w-lg text-base leading-7 text-ink/55">{{ $tour->short_description }}</p>
                @if($includedServices->isNotEmpty())
                    <div class="mt-6 md:hidden">
                        <p class="text-xs font-semibold text-forest">Dịch vụ trong gói</p>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-ink/55">{{ $includedServices->pluck('name')->join(' · ') }}</p>
                    </div>
                @endif
            </div>
            <div class="mt-10 flex items-end justify-between gap-6 border-t border-ink/12 pt-6">
                <div>
                    <span class="text-xs text-ink/45">Giá từ</span>
                    <div class="mt-1 text-2xl font-semibold">{{ number_format((float) $tour->base_price, 0, ',', '.') }} {{ $tour->currency }}</div>
                </div>
                <span class="border-b border-ink pb-1 text-sm font-semibold">Xem chi tiết</span>
            </div>
        </div>
    </a>
</article>

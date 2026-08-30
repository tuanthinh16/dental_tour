@php($editorMode = $editorMode ?? false)
@php($includedServices = $tour->services->where('is_active', true)->take(5))
@php($hasPrice = (float) $tour->base_price > 0)

<article data-tour-card class="group relative overflow-hidden rounded-[2rem] bg-white text-ink shadow-[0_28px_75px_rgba(0,0,0,0.2)] md:rounded-[2.5rem]">
    @if($editorMode)
        <button data-visual-editor-open="edit-tour-{{ $tour->id }}" type="button" class="accent-contrast absolute right-5 top-5 z-50 rounded-full bg-coral px-4 py-2 text-xs font-semibold shadow-xl transition-transform duration-300 hover:scale-105">Sửa</button>
    @endif
    <a href="{{ route('tours.show', $tour->slug) }}" @class(['block', 'pointer-events-none' => $editorMode])>
        <div class="relative aspect-[5/4] overflow-hidden bg-sand">
            <div data-tour-card-image class="absolute inset-0 scale-[0.94]">
                <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 ease-out group-hover:scale-105" style="background-image: url('{{ $tour->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($tour->slug).'/1200/900' }}')"></div>
            </div>
            <div class="absolute inset-0 bg-gradient-to-t from-ink/60 via-transparent to-ink/10"></div>
            @if($includedServices->isNotEmpty())
                <div class="absolute inset-0 hidden flex-col justify-end bg-ink/92 p-7 text-white opacity-0 backdrop-blur-sm transition-all duration-500 group-hover:opacity-100 group-focus-within:opacity-100 md:flex md:p-8">
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
        <div class="flex min-h-[18rem] flex-col justify-between p-7 md:p-9">
            <div>
                <div class="flex items-center justify-between gap-4 text-sm font-medium text-forest">
                    <span>{{ $tour->destination?->name ?: 'Việt Nam' }}</span>
                    <span class="h-px flex-1 bg-ink/12"></span>
                    <span>{{ $tour->duration_days ?: 1 }} ngày</span>
                </div>
                <h3 class="mt-5 text-3xl font-semibold leading-[1.02] tracking-[-0.045em] md:text-[2.15rem]">{{ $tour->name }}</h3>
                <p class="mt-4 line-clamp-2 max-w-lg text-sm leading-6 text-ink/55 md:text-base md:leading-7">{{ $tour->short_description }}</p>
                @if($includedServices->isNotEmpty())
                    <div class="mt-5 md:hidden">
                        <p class="text-xs font-semibold text-forest">Dịch vụ trong gói</p>
                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-ink/55">{{ $includedServices->pluck('name')->join(' · ') }}</p>
                    </div>
                @endif
            </div>
            <div class="mt-8 flex items-end justify-between gap-6 border-t border-ink/12 pt-5">
                <div>
                    <span class="text-xs text-ink/45">{{ $hasPrice ? 'Giá từ' : 'Giá dịch vụ' }}</span>
                    <div class="mt-1 text-xl font-semibold">{{ $hasPrice ? \App\Support\MoneyFormatter::format($tour->base_price, $tour->currency) : 'Liên hệ' }}</div>
                </div>
                <span class="flex items-center gap-2 text-sm font-semibold">Xem chi tiết <span aria-hidden="true">↗</span></span>
            </div>
        </div>
    </a>
    @if($editorMode)
        @include('admin.landing-editor.tour-dialog', ['tour' => $tour])
    @endif
</article>

@extends('layouts.admin')

@section('title', 'Giao diện website')

@section('content')
    <div data-settings-tabs data-initial-tab="{{ request('tab', 'theme') }}">
        <div class="mb-6 flex gap-2 border-b border-slate-200 pb-4">
            <button data-settings-tab="theme" type="button" title="Mở cài đặt giao diện" class="bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white">Giao diện</button>
            <button data-settings-tab="seo" type="button" title="Mở cài đặt SEO" class="px-4 py-2.5 text-sm font-semibold text-slate-500 transition-colors hover:text-slate-950">SEO</button>
        </div>

    <form data-settings-tab-panel="theme" data-theme-form method="post" action="{{ route('admin.settings.theme.update') }}" class="min-w-0">
        @csrf
        @method('PUT')

        <div class="flex min-w-0 flex-col justify-between gap-6 lg:flex-row lg:items-end">
            <div class="min-w-0">
                <p class="text-sm font-semibold text-brand-600">Tùy chỉnh trực quan</p>
                <h1 class="mt-2 max-w-4xl text-4xl font-semibold tracking-[-0.04em] text-slate-950 md:text-5xl">Giao diện website</h1>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-500">Điều chỉnh bảng màu và font theo từng vai trò. Mẫu font cập nhật trực tiếp, còn bản xem trước website được mở khi bạn cần kiểm tra tổng thể.</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <button data-theme-preview-open type="button" title="Mở xem trước giao diện website" class="rounded-full border border-slate-950 px-5 py-2.5 text-sm font-semibold text-slate-950 transition-colors hover:bg-slate-950 hover:text-white">Xem trước website</button>
                <button data-theme-reset type="button" title="Khôi phục màu và font mặc định" class="rounded-full border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-950 hover:text-white">Khôi phục mặc định</button>
                <button title="Lưu cài đặt giao diện" class="rounded-full bg-slate-950 px-6 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105">Lưu giao diện</button>
            </div>
        </div>

        <div class="mt-10 max-w-5xl space-y-8">
            <section class="min-w-0 bg-white p-6 shadow-sm md:p-8">
                <h2 class="text-2xl font-semibold tracking-[-0.03em] text-slate-950">Bảng màu</h2>
                <p class="mt-2 text-sm text-slate-500">Màu chữ tương phản cho CTA được hệ thống tự tính.</p>

                <div class="mt-7 grid min-w-0 grid-flow-dense grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-12">
                    @foreach([
                        'ui_color_primary' => ['Màu thương hiệu', '#123D34'],
                        'ui_color_accent' => ['Màu nhấn', '#FF6C4F'],
                        'ui_color_background' => ['Màu nền', '#FAF8F2'],
                        'ui_color_text' => ['Màu chữ', '#0B1F1B'],
                    ] as $key => [$label, $default])
                        <label class="group min-w-0 border border-slate-200 p-4 md:col-span-3">
                            <span class="text-xs font-semibold text-slate-500">{{ $label }}</span>
                            <span class="mt-4 flex min-w-0 items-center gap-3">
                                <input data-theme-control data-css-variable="--{{ str_replace('_', '-', $key) }}" data-default="{{ $default }}" type="color" name="{{ $key }}" value="{{ old($key, $theme[$key]) }}" class="size-11 shrink-0 cursor-pointer border-0 bg-transparent p-0">
                                <span data-color-value class="min-w-0 truncate font-mono text-xs text-slate-700">{{ old($key, $theme[$key]) }}</span>
                            </span>
                        </label>
                    @endforeach
                </div>

                <label class="mt-3 flex min-w-0 flex-col gap-4 border border-slate-200 p-4 sm:flex-row sm:items-center sm:justify-between">
                    <span class="min-w-0">
                        <span class="block text-xs font-semibold text-slate-500">Màu bề mặt phụ</span>
                        <span class="mt-1 block text-xs text-slate-400">Dùng cho card sáng và vùng nội dung phụ.</span>
                    </span>
                    <span class="flex shrink-0 items-center gap-3">
                        <span data-color-value class="font-mono text-xs text-slate-700">{{ old('ui_color_surface', $theme['ui_color_surface']) }}</span>
                        <input data-theme-control data-css-variable="--ui-color-surface" data-default="#F3EEE3" type="color" name="ui_color_surface" value="{{ old('ui_color_surface', $theme['ui_color_surface']) }}" class="size-11 cursor-pointer border-0 bg-transparent p-0">
                    </span>
                </label>
            </section>

            <section class="min-w-0 bg-white p-6 shadow-sm md:p-8">
                <h2 class="text-2xl font-semibold tracking-[-0.03em] text-slate-950">Typography</h2>
                <p class="mt-2 text-sm text-slate-500">Chọn font riêng cho điều hướng, tiêu đề và nội dung dài.</p>

                <div class="mt-7 divide-y divide-slate-200">
                    @foreach([
                        'ui_font_header' => ['Font header', 'Logo, menu và điều hướng', 'DENTALTOUR / Trang chủ / Tour'],
                        'ui_font_title' => ['Font tiêu đề', 'H1, H2, H3 và tiêu đề tour', 'Đi xa để thấy mình gần hơn.'],
                        'ui_font_body' => ['Font nội dung', 'Đoạn văn, form và thông tin chi tiết', 'Một hành trình được thiết kế theo nhịp riêng của bạn.'],
                    ] as $key => [$label, $description, $sample])
                        <div class="grid min-w-0 gap-4 py-5 first:pt-0 last:pb-0 lg:grid-cols-[minmax(10rem,0.8fr)_minmax(14rem,1.35fr)_minmax(11rem,0.85fr)] lg:items-center">
                            <label for="{{ $key }}" class="min-w-0">
                                <span class="block text-sm font-semibold text-slate-800">{{ $label }}</span>
                                <span class="mt-1 block text-xs leading-5 text-slate-400">{{ $description }}</span>
                            </label>
                            <div class="min-w-0 border-l-2 border-brand-500 pl-4">
                                <span class="mb-1 block text-[10px] font-semibold uppercase tracking-[0.14em] text-slate-400">Mẫu hiển thị</span>
                                <p data-font-preview="{{ $key }}" class="truncate text-lg text-slate-900" style="font-family: {{ $theme[$key.'_stack'] }}" title="{{ $sample }}">{{ $sample }}</p>
                            </div>
                            <select id="{{ $key }}" data-theme-font data-font-key="{{ $key }}" data-css-variable="--{{ str_replace('_', '-', $key) }}" data-default="{{ \App\Support\ThemeOptions::DEFAULTS[$key] }}" name="{{ $key }}" class="min-w-0 w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm outline-none focus:border-brand-500">
                                @foreach($fonts as $font)
                                    <option value="{{ $font }}" @selected(old($key, $theme[$key]) === $font)>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </form>

    <section data-settings-tab-panel="seo" hidden class="max-w-5xl">
        <div class="flex flex-col justify-between gap-5 border-b border-slate-200 pb-6 md:flex-row md:items-end">
            <div>
                <p class="text-sm font-semibold text-brand-600">Hiển thị trên công cụ tìm kiếm</p>
                <h1 class="mt-2 text-4xl font-semibold tracking-[-0.04em] text-slate-950 md:text-5xl">SEO website</h1>
                <p class="mt-4 max-w-2xl text-sm leading-6 text-slate-500">Thiết lập metadata mặc định cho trang chủ, danh sách tour và những trang chưa có nội dung SEO riêng.</p>
            </div>
            <a href="{{ route('sitemap') }}" target="_blank" rel="noopener" title="Mở sitemap XML trong tab mới" class="shrink-0 border border-slate-300 px-5 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-950 hover:text-white">Xem sitemap</a>
        </div>

        <form method="post" enctype="multipart/form-data" action="{{ route('admin.settings.seo.update') }}" class="mt-8 grid gap-5 bg-white p-6 shadow-sm md:grid-cols-2 md:p-8">
            @csrf
            @method('PUT')
            <label class="text-sm font-semibold text-slate-800">SEO title mặc định *
                <input name="seo_site_title" required maxlength="255" value="{{ old('seo_site_title', $seo['seo_site_title']) }}" class="mt-2 w-full border border-slate-200 bg-slate-50 px-4 py-3 font-normal outline-none focus:border-brand-500">
                <span class="mt-2 block text-xs font-normal text-slate-400">Nên giữ trong khoảng 50–60 ký tự.</span>
            </label>
            <label class="text-sm font-semibold text-slate-800">Keywords *
                <input name="seo_keywords" required maxlength="1000" value="{{ old('seo_keywords', $seo['seo_keywords']) }}" class="mt-2 w-full border border-slate-200 bg-slate-50 px-4 py-3 font-normal outline-none focus:border-brand-500">
                <span class="mt-2 block text-xs font-normal text-slate-400">Ngăn cách các từ khóa bằng dấu phẩy.</span>
            </label>
            <label class="text-sm font-semibold text-slate-800 md:col-span-2">SEO description mặc định *
                <textarea name="seo_site_description" rows="4" required maxlength="500" class="mt-2 w-full border border-slate-200 bg-slate-50 px-4 py-3 font-normal outline-none focus:border-brand-500">{{ old('seo_site_description', $seo['seo_site_description']) }}</textarea>
                <span class="mt-2 block text-xs font-normal text-slate-400">Nên giữ trong khoảng 120–160 ký tự.</span>
            </label>
            <label class="text-sm font-semibold text-slate-800 md:col-span-2">Ảnh chia sẻ mạng xã hội
                <span class="mt-3 grid gap-4 md:grid-cols-[14rem_1fr] md:items-center">
                    <span data-image-preview class="grid aspect-[1.91/1] place-items-center overflow-hidden bg-slate-100 text-xs font-normal text-slate-400">
                        @if($seo['seo_og_image'])
                            <img src="{{ $seo['seo_og_image'] }}" alt="Ảnh chia sẻ mạng xã hội hiện tại" class="h-full w-full object-cover">
                        @else
                            Chưa có ảnh chia sẻ
                        @endif
                    </span>
                    <span>
                        <input data-image-input type="file" name="seo_og_image_upload" accept="image/jpeg,image/png,image/webp" class="block w-full cursor-pointer border border-dashed border-slate-300 bg-slate-50 px-4 py-5 text-sm file:mr-3 file:border-0 file:bg-slate-950 file:px-4 file:py-2 file:text-white">
                        <span class="mt-2 block text-xs font-normal text-slate-400">JPG, PNG hoặc WebP, tối đa 8 MB. Khuyến nghị ảnh 1200 × 630 px.</span>
                    </span>
                </span>
            </label>
            <label class="text-sm font-semibold text-slate-800 md:col-span-2">Hoặc dùng URL ảnh ngoài
                <input name="seo_og_image" type="url" maxlength="2048" value="{{ old('seo_og_image', $seo['seo_og_image']) }}" placeholder="https://..." class="mt-2 w-full border border-slate-200 bg-slate-50 px-4 py-3 font-normal outline-none focus:border-brand-500">
                <span class="mt-2 block text-xs font-normal text-slate-400">Nếu upload ảnh mới, ảnh upload sẽ được ưu tiên.</span>
            </label>
            <label class="text-sm font-semibold text-slate-800 md:col-span-2">Đường dẫn bổ sung trong sitemap XML
                <textarea name="seo_sitemap_urls" rows="5" maxlength="10000" placeholder="/gioi-thieu&#10;/chinh-sach-bao-mat" class="mt-2 w-full border border-slate-200 bg-slate-50 px-4 py-3 font-normal outline-none focus:border-brand-500">{{ old('seo_sitemap_urls', $seo['seo_sitemap_urls']) }}</textarea>
                <span class="mt-2 block text-xs font-normal text-slate-400">Mỗi dòng một đường dẫn nội bộ bắt đầu bằng /. Trang chủ, danh sách tour và tour đang hiển thị được cập nhật tự động.</span>
            </label>
            <div class="flex flex-wrap gap-3 border-t border-slate-200 pt-5 md:col-span-2">
                <button title="Lưu cấu hình SEO" class="rounded-full bg-slate-950 px-6 py-2.5 text-sm font-semibold text-white transition-transform hover:scale-105">Lưu SEO</button>
                <a href="{{ route('robots') }}" target="_blank" rel="noopener" title="Mở robots.txt trong tab mới" class="rounded-full border border-slate-300 px-6 py-2.5 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-950 hover:text-white">Kiểm tra robots.txt</a>
            </div>
        </form>
    </section>

    <div data-theme-preview-modal class="fixed inset-0 z-[100] hidden overflow-y-auto bg-slate-950/70 p-3 backdrop-blur-sm sm:p-6 lg:p-10" role="dialog" aria-modal="true" aria-labelledby="theme-preview-title">
        <button data-theme-preview-close type="button" title="Đóng xem trước" class="absolute inset-0 size-full cursor-default" aria-label="Đóng xem trước"></button>
        <div data-theme-preview-dialog class="relative mx-auto w-full max-w-6xl overflow-hidden bg-white shadow-[0_40px_120px_rgba(0,0,0,0.35)]">
            <div class="flex items-center justify-between border-b border-slate-200 bg-white px-5 py-4 md:px-7">
                <div class="min-w-0">
                    <h2 id="theme-preview-title" class="truncate text-base font-semibold text-slate-950">Xem trước website</h2>
                    <p class="mt-1 text-xs text-slate-400">Thay đổi được cập nhật trực tiếp và chưa được lưu.</p>
                </div>
                <button data-theme-preview-close data-theme-preview-close-button type="button" title="Đóng xem trước website" class="ml-5 shrink-0 rounded-full border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 transition-colors hover:bg-slate-950 hover:text-white">Đóng</button>
            </div>

            <div data-theme-preview class="overflow-hidden bg-cream text-ink" style="--ui-color-primary: {{ $theme['ui_color_primary'] }}; --ui-color-accent: {{ $theme['ui_color_accent'] }}; --ui-color-background: {{ $theme['ui_color_background'] }}; --ui-color-text: {{ $theme['ui_color_text'] }}; --ui-color-surface: {{ $theme['ui_color_surface'] }}; --ui-color-primary-contrast: {{ $theme['ui_color_primary_contrast'] }}; --ui-color-accent-contrast: {{ $theme['ui_color_accent_contrast'] }}; --ui-color-text-contrast: {{ $theme['ui_color_text_contrast'] }}; --ui-font-header: {{ $theme['ui_font_header_stack'] }}; --ui-font-title: {{ $theme['ui_font_title_stack'] }}; --ui-font-body: {{ $theme['ui_font_body_stack'] }};">
                <div class="public-site-header ink-contrast flex items-center justify-between bg-ink px-5 py-4 text-white md:px-8">
                    <span class="font-bold tracking-[-0.04em]">DENTAL<span class="text-coral">TOUR</span></span>
                    <div class="flex items-center gap-4 text-xs md:gap-6"><span>Tour</span><span class="rounded-full bg-white px-4 py-2 text-slate-950">Tư vấn</span></div>
                </div>

                <div class="bg-cream px-6 py-12 md:px-12 md:py-20">
                    <p class="text-xs font-semibold text-forest">Hành trình Việt Nam</p>
                    <h2 class="mt-4 max-w-5xl text-4xl font-semibold leading-[0.98] tracking-[-0.055em] md:text-7xl">
                        Đi xa để thấy
                        <span class="inline-block h-[0.58em] w-20 rounded-full bg-cover bg-center align-middle md:w-28" style="background-image: url('https://picsum.photos/seed/theme-preview-vietnam/300/140')"></span>
                        mình gần hơn.
                    </h2>
                    <p class="mt-6 max-w-xl text-sm leading-6 opacity-60">Một trải nghiệm mẫu để kiểm tra font tiêu đề, font nội dung và khả năng tương phản của bảng màu trước khi lưu.</p>
                    <div class="mt-8 flex flex-wrap gap-3"><span class="accent-contrast bg-coral px-5 py-3 text-xs font-semibold">Khám phá tour</span><span class="border border-ink/25 px-5 py-3 text-xs font-semibold">Tìm hiểu thêm</span></div>
                </div>

                <div class="overflow-hidden border-y border-ink/10 bg-sand py-3">
                    <div data-theme-marquee class="flex w-max items-center whitespace-nowrap text-xs font-semibold">
                        @foreach([1, 2] as $copy)
                            <div class="flex items-center gap-8 pr-8"><span>Header type</span><span class="text-coral">Title type</span><span>Body type</span><span class="text-forest">Travel with intention</span></div>
                        @endforeach
                    </div>
                </div>

                <div class="grid gap-3 bg-cream p-5 md:grid-cols-2">
                    <div class="bg-forest p-6 primary-contrast"><p class="text-xs opacity-60">Primary</p><p class="mt-12 text-xl font-semibold">Không gian thương hiệu</p></div>
                    <div class="bg-sand p-6">
                        <div data-testimonial><p class="text-lg font-semibold leading-6">“Tinh tế, dễ đọc và có cá tính riêng.”</p></div>
                        <div data-testimonial class="hidden"><p class="text-lg font-semibold leading-6">“Màu sắc phản ánh đúng tinh thần hành trình.”</p></div>
                        <div class="mt-8 flex gap-2">
                            <button type="button" data-testimonial-prev class="grid size-8 place-items-center rounded-full border border-ink/20" aria-label="Nhận xét trước">‹</button>
                            <button type="button" data-testimonial-next class="ink-contrast grid size-8 place-items-center rounded-full bg-ink" aria-label="Nhận xét tiếp theo">›</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

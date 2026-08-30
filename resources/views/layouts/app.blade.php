<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'Khám phá những hành trình đáng nhớ cùng Dental Tour.')">
    <title>@yield('title', 'Dental Tour')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://api.fontshare.com/v2/css?f[]=satoshi@300,400,500,600,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600;700&family=Geist:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Lora:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body
    class="bg-cream font-sans text-ink antialiased"
    style="--ui-color-primary: {{ $themeSettings['ui_color_primary'] }}; --ui-color-accent: {{ $themeSettings['ui_color_accent'] }}; --ui-color-background: {{ $themeSettings['ui_color_background'] }}; --ui-color-text: {{ $themeSettings['ui_color_text'] }}; --ui-color-surface: {{ $themeSettings['ui_color_surface'] }}; --ui-color-primary-contrast: {{ $themeSettings['ui_color_primary_contrast'] }}; --ui-color-accent-contrast: {{ $themeSettings['ui_color_accent_contrast'] }}; --ui-color-text-contrast: {{ $themeSettings['ui_color_text_contrast'] }}; --ui-font-header: {{ $themeSettings['ui_font_header_stack'] }}; --ui-font-title: {{ $themeSettings['ui_font_title_stack'] }}; --ui-font-body: {{ $themeSettings['ui_font_body_stack'] }};"
>
    <header data-site-header class="public-site-header fixed inset-x-0 top-0 z-50 px-4 pt-4 md:px-8 md:pt-6">
        <div data-site-header-shell class="site-header-shell ink-contrast mx-auto flex max-w-[90rem] items-center justify-between rounded-full px-5 py-3 text-white md:px-7">
            <a href="{{ route('home') }}" class="text-base font-extrabold tracking-[-0.04em] md:text-lg">
                DENTAL<span class="text-coral">TOUR</span>
            </a>
            <nav class="flex items-center gap-4 text-sm font-medium md:gap-8">
                <a href="{{ route('home') }}" class="hidden transition-colors hover:text-coral sm:block">Trang chủ</a>
                <a href="{{ route('tours.index') }}" class="transition-colors hover:text-coral">Tour</a>
                <a href="{{ route('home') }}#consultation" class="rounded-full bg-white px-4 py-2 text-ink transition-transform hover:scale-105 md:px-6">Nhận tư vấn</a>
            </nav>
        </div>
    </header>

    @if(session('success'))
        <div class="primary-contrast fixed right-5 top-28 z-[60] max-w-sm rounded-2xl bg-forest px-5 py-4 text-white shadow-2xl">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="fixed left-1/2 top-28 z-[60] w-[calc(100%-2rem)] max-w-xl -translate-x-1/2 rounded-2xl bg-red-50 px-5 py-4 text-sm text-red-800 shadow-2xl">
            {{ $errors->first() }}
        </div>
    @endif

    <main class="w-full max-w-full overflow-x-hidden">
        @yield('content')
    </main>

    <footer class="ink-contrast bg-ink text-white">
        <div class="mx-auto grid max-w-[90rem] gap-14 px-6 py-20 md:grid-cols-[1.5fr_1fr_1fr] md:px-10 lg:py-28">
            <div>
                <div class="text-2xl font-extrabold tracking-[-0.05em]">DENTAL<span class="text-coral">TOUR</span></div>
                <p class="mt-6 max-w-md text-base leading-7 text-white/55">Hành trình được thiết kế riêng, kết nối văn hóa bản địa với dịch vụ tận tâm.</p>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">Khám phá</h3>
                <div class="mt-5 space-y-3 text-sm text-white/55">
                    <a class="block transition-colors hover:text-white" href="{{ route('tours.index') }}">Tất cả tour</a>
                    <a class="block transition-colors hover:text-white" href="{{ route('home') }}#destinations">Điểm đến</a>
                </div>
            </div>
            <div>
                <h3 class="text-sm font-semibold text-white">Liên hệ</h3>
                <p class="mt-5 text-sm leading-7 text-white/55">
                    {{ ($settings ?? [])['contact_email'] ?? 'hello@dentaltour.vn' }}<br>
                    {{ ($settings ?? [])['contact_phone'] ?? '+84 900 000 000' }}
                </p>
            </div>
        </div>
        <div class="border-t border-white/10 px-6 py-6 text-center text-xs text-white/35">Copyright {{ date('Y') }} Dental Tour</div>
    </footer>
</body>
</html>

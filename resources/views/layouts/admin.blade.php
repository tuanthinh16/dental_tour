<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Admin') · Dental Tour</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500;600;700&family=Geist:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&family=Lora:wght@400;500;600;700&family=Manrope:wght@300;400;500;600;700&family=Outfit:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-cream text-ink">
    <header class="public-site-header fixed inset-x-0 top-0 z-50 px-4 pt-4 md:px-8 md:pt-6">
        <div class="ink-contrast mx-auto flex max-w-[90rem] items-center gap-4 rounded-full bg-ink px-5 py-3 text-white shadow-[0_18px_50px_rgba(5,24,20,0.2)] md:px-7">
            <a href="{{ route('admin.dashboard') }}" class="shrink-0 text-base font-extrabold tracking-[-0.04em] md:text-lg">DENTAL<span class="text-coral">TOUR</span><span class="ml-2 text-[10px] font-medium uppercase tracking-[0.16em] text-white/45">CMS</span></a>
            <nav class="hidden min-w-0 flex-1 items-center justify-center gap-5 overflow-x-auto whitespace-nowrap text-sm font-medium text-white/65 lg:flex">
                @foreach([
                    ['dashboard', 'Tổng quan'],
                    ['landing-editor', 'Visual Editor'],
                    ['destinations.index', 'Điểm đến'],
                    ['included-services.index', 'Dịch vụ'],
                    ['tours.index', 'Tour'],
                    ['consultations.index', 'Tư vấn'],
                    ['pages.index', 'Trang nội dung'],
                    ['settings.index', 'Giao diện'],
                ] as [$route, $label])
                    <a href="{{ route('admin.'.$route) }}" class="transition-colors hover:text-white">{{ $label }}</a>
                @endforeach
            </nav>
            <a href="{{ route('admin.landing-editor') }}" class="accent-contrast ml-auto shrink-0 rounded-full bg-coral px-4 py-2 text-xs font-semibold transition-transform hover:scale-105 md:px-5 md:text-sm">Chỉnh landing</a>
            <button id="logout" type="button" class="hidden shrink-0 text-xs text-white/45 transition-colors hover:text-white lg:block">Đăng xuất</button>
        </div>
        <nav class="mx-auto mt-2 flex max-w-[90rem] gap-4 overflow-x-auto px-2 pb-1 text-xs font-medium text-ink/55 lg:hidden">
            @foreach([
                ['dashboard', 'Tổng quan'],
                ['landing-editor', 'Visual Editor'],
                ['destinations.index', 'Điểm đến'],
                ['tours.index', 'Tour'],
                ['pages.index', 'Nội dung'],
                ['settings.index', 'Giao diện'],
            ] as [$route, $label])
                <a href="{{ route('admin.'.$route) }}" class="shrink-0">{{ $label }}</a>
            @endforeach
        </nav>
    </header>

    <main class="w-full max-w-full overflow-x-hidden px-5 pb-16 pt-32 md:px-10 md:pt-36 lg:pt-40">
        <div class="mx-auto max-w-[90rem]">
            @if(session('success'))
                <div class="mb-5 bg-mint px-5 py-4 text-sm font-medium text-ink">{{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-5 bg-red-50 px-5 py-4 text-sm font-medium text-red-800">{{ $errors->first() }}</div>
            @endif
            @yield('content')
        </div>
    </main>

    <script>
        document.getElementById('logout')?.addEventListener('click', async () => {
            await fetch('/api/admin/auth/logout', { method: 'POST', headers: { Accept: 'application/json' } });
            location.href = '{{ route('admin.login') }}';
        });
    </script>
</body>
</html>

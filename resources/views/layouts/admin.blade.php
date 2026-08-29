<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title','Admin') · Dental Tour</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="min-h-screen lg:flex">
        <aside class="w-full bg-brand-900 p-6 text-white lg:min-h-screen lg:w-64">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-black">DENTAL<span class="text-emerald-300">TOUR</span></a>
            <nav class="mt-8 grid gap-1 text-sm">
                @foreach([
                    ['dashboard','Tổng quan'],
                    ['destinations.index','Điểm đến'],
                    ['tour-categories.index','Danh mục tour'],
                    ['included-services.index','Dịch vụ đi kèm'],
                    ['tours.index','Tour'],
                    ['consultations.index','Yêu cầu tư vấn'],
                    ['pages.index','Trang nội dung'],
                    ['settings.index','Cài đặt'],
                ] as $link)
                    <a href="{{ route('admin.'.$link[0]) }}" class="rounded-lg px-3 py-2.5 text-white/75 hover:bg-white/10 hover:text-white">{{ $link[1] }}</a>
                @endforeach
            </nav>
            <button id="logout" class="mt-8 text-sm text-white/50">Đăng xuất</button>
        </aside>
        <main class="min-w-0 flex-1 p-5 lg:p-10">
            <div class="mx-auto max-w-6xl">
                @if(session('success'))
                    <div class="mb-5 rounded-xl bg-emerald-100 px-5 py-3 text-emerald-800">{{ session('success') }}</div>
                @endif
                @if($errors->any())
                    <div class="mb-5 rounded-xl bg-red-100 px-5 py-3 text-red-700">{{ $errors->first() }}</div>
                @endif
                @yield('content')
            </div>
        </main>
    </div>
    <script>
        document.getElementById('logout')?.addEventListener('click', async () => {
            await fetch('/api/admin/auth/logout', { method: 'POST', headers: { Accept: 'application/json' } });
            location.href = '{{ route('admin.login') }}';
        });
    </script>
</body>
</html>

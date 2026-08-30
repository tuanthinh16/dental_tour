@extends('layouts.admin')

@section('title', 'Tổng quan')

@section('content')
    <div class="bg-ink px-6 py-10 text-white shadow-[0_30px_80px_rgba(5,24,20,0.2)] md:px-10 md:py-14">
        <p class="text-sm font-semibold text-coral">Trung tâm nội dung</p>
        <h1 class="mt-4 max-w-4xl text-4xl font-semibold tracking-[-0.05em] md:text-6xl">Quản lý hành trình, dịch vụ và yêu cầu tư vấn.</h1>
        <div class="mt-8 flex flex-wrap gap-3">
            <a href="{{ route('admin.pages.index') }}#create-pages" class="accent-contrast rounded-full bg-coral px-6 py-3 text-sm font-semibold transition-transform hover:scale-105">Thêm nội dung</a>
            <a href="{{ route('admin.tours.index') }}#create-tours" class="rounded-full bg-white px-6 py-3 text-sm font-semibold text-ink transition-transform hover:scale-105">Thêm tour</a>
            <a href="{{ route('admin.destinations.index') }}#create-destinations" class="rounded-full border border-white/35 px-6 py-3 text-sm font-semibold text-white transition-colors hover:bg-white hover:text-ink">Thêm điểm đến</a>
        </div>
    </div>
    <div class="mt-6 grid grid-flow-dense gap-3 md:grid-cols-12">
        @foreach([['Tour', $tourCount, 'md:col-span-5'], ['Điểm đến', $destinationCount, 'md:col-span-3'], ['Lead mới', $newLeadCount, 'md:col-span-4']] as [$label, $value, $span])
            <div data-motion-card class="bg-white p-7 shadow-sm {{ $span }}">
                <p class="text-sm text-ink/50">{{ $label }}</p>
                <p class="mt-3 text-5xl font-semibold tracking-[-0.05em] text-ink">{{ $value }}</p>
            </div>
        @endforeach
    </div>
@endsection

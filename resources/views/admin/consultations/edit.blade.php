@extends('layouts.admin')

@section('title', 'Xử lý yêu cầu tư vấn')

@section('content')
    <div>
        <p class="text-sm font-semibold text-brand-600">Khách hàng</p>
        <h1 class="mt-2 max-w-5xl text-4xl font-semibold tracking-[-0.05em] text-ink md:text-6xl">Yêu cầu từ {{ $item->full_name }}</h1>
    </div>
    <div class="mt-10 grid gap-5 lg:grid-cols-[0.9fr_1.1fr]">
        <section class="bg-ink p-7 text-white shadow-[0_24px_70px_rgba(5,24,20,0.16)] md:p-9">
            <dl class="grid gap-6 text-sm">
                @foreach([
                    'Email' => $item->email,
                    'Điện thoại' => $item->phone,
                    'Quốc gia' => $item->country ?? 'Chưa cung cấp',
                    'Tour' => $item->tour?->name ?? 'Chưa chọn tour',
                    'Ngày đi' => $item->travel_date?->format('d/m/Y') ?? 'Chưa chọn',
                    'Số người' => $item->number_of_people ?? 'Chưa cung cấp',
                ] as $label => $value)
                    <div>
                        <dt class="text-[10px] font-semibold uppercase tracking-[0.16em] text-white/40">{{ $label }}</dt>
                        <dd class="mt-2 font-semibold text-white">{{ $value }}</dd>
                    </div>
                @endforeach
            </dl>
        </section>
        <form method="post" action="{{ route('admin.consultations.update', $item) }}" class="bg-white p-7 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
            @csrf
            @method('PUT')
            <label class="text-sm font-semibold text-ink">Trạng thái
                <select name="status" class="mt-3 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-brand-500">
                    @foreach(AppModelsConsultationRequest::STATUSES as $status)
                        <option @selected(old('status', $item->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </label>
            <label class="mt-6 block text-sm font-semibold text-ink">Nội dung khách gửi
                <textarea name="message" rows="8" class="mt-3 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-brand-500">{{ old('message', $item->message) }}</textarea>
            </label>
            <div class="mt-8 flex flex-wrap gap-3">
                <button class="accent-contrast rounded-full bg-coral px-6 py-3 text-sm font-semibold transition-transform hover:scale-105">Cập nhật</button>
                <a href="{{ route('admin.consultations.index') }}" class="rounded-full border border-ink/20 px-6 py-3 text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Quay lại</a>
            </div>
        </form>
    </div>
@endsection

<form id="consultation" method="post" action="{{ route('consultation.store') }}" class="ink-contrast bg-ink p-7 text-white shadow-[0_35px_100px_rgba(11,31,27,0.24)] md:p-12">
    @csrf
    <div class="flex items-start justify-between gap-6 border-b border-white/15 pb-8">
        <div>
            <h3 class="text-3xl font-semibold tracking-[-0.04em]">Bắt đầu cuộc trò chuyện</h3>
            <p class="mt-3 max-w-lg text-sm leading-6 text-white/50">Thông tin của bạn chỉ được dùng để tư vấn hành trình.</p>
        </div>
        <span class="hidden text-sm text-coral sm:block">Phản hồi trong 24 giờ</span>
    </div>
    <div class="mt-7 grid gap-x-8 gap-y-3 sm:grid-cols-2">
        <input class="field-control" name="full_name" value="{{ old('full_name') }}" placeholder="Họ và tên *" required>
        <input class="field-control" type="email" name="email" value="{{ old('email') }}" placeholder="Email *" required>
        <input class="field-control" name="phone" value="{{ old('phone') }}" placeholder="Số điện thoại *" required>
        <input class="field-control" name="country" value="{{ old('country') }}" placeholder="Quốc gia">
        <input class="field-control" type="date" name="travel_date" value="{{ old('travel_date') }}" aria-label="Ngày dự kiến">
        <input class="field-control" type="number" min="1" name="number_of_people" value="{{ old('number_of_people') }}" placeholder="Số người">
        <select class="field-control sm:col-span-2" name="tour_id">
            <option value="">Tour quan tâm</option>
            @foreach($allTours as $option)
                <option value="{{ $option->id }}" @selected(old('tour_id', $selectedTour?->id) == $option->id)>{{ $option->translated('name') }}</option>
            @endforeach
        </select>
        <textarea class="field-control min-h-28 sm:col-span-2" name="message" placeholder="Bạn mong muốn điều gì ở chuyến đi?">{{ old('message') }}</textarea>
    </div>
    <input type="hidden" name="utm_source" value="{{ request('utm_source') }}">
    <input type="hidden" name="utm_medium" value="{{ request('utm_medium') }}">
    <input type="hidden" name="utm_campaign" value="{{ request('utm_campaign') }}">
    <button title="Gửi thông tin để nhận tư vấn" class="accent-contrast mt-9 w-full bg-coral px-6 py-4 font-semibold transition-transform hover:scale-[1.01]">Gửi yêu cầu tư vấn</button>
</form>

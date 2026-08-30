<article data-tour-card class="group relative min-h-[38rem] overflow-hidden rounded-[2rem] bg-white text-ink shadow-[0_28px_75px_rgba(0,0,0,0.2)] md:rounded-[2.5rem]">
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_70%_25%,rgba(255,108,79,0.16),transparent_38%)]"></div>
    <button data-visual-editor-open="create-tour-inline" type="button" aria-expanded="false" class="absolute inset-0 z-10 flex w-full flex-col items-center justify-center p-8 text-center transition-colors duration-500 hover:bg-sand/55">
        <span class="accent-contrast grid size-16 place-items-center rounded-full bg-coral text-3xl shadow-xl transition-transform duration-500 group-hover:scale-110">+</span>
        <span class="mt-6 text-3xl font-semibold tracking-[-0.04em]">Thêm tour</span>
        <span class="mt-3 max-w-sm text-sm leading-6 text-ink/50">Tạo một card mới đúng tại vị trí cuối danh sách.</span>
    </button>
    <div id="create-tour-inline" data-visual-editor-panel hidden class="absolute inset-0 z-20 overflow-y-auto bg-white p-7 md:p-10">
        <div class="flex items-start justify-between gap-5 border-b border-ink/10 pb-6">
            <div><p class="text-sm font-semibold text-coral">Tour mới</p><h3 class="mt-2 text-3xl font-semibold tracking-[-0.04em]">Nhập nội dung card mới.</h3><p class="mt-2 text-xs text-ink/45">Ảnh và các trường có dấu * là bắt buộc.</p></div>
            <button data-visual-editor-close type="button" class="rounded-full border border-ink/15 px-4 py-2 text-xs font-semibold">Đóng</button>
        </div>
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.landing-editor.tours.store') }}" class="mt-7 grid gap-4 md:grid-cols-2">
            @csrf
            <label class="text-xs font-semibold md:col-span-2">Tên tour *<input name="name" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Điểm đến<select name="destination_id" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"><option value="">Chưa chọn</option>@foreach($destinations as $destination)<option value="{{ $destination->id }}">{{ $destination->name }}</option>@endforeach</select></label>
            <label class="text-xs font-semibold">Số ngày *<input name="duration_days" type="number" min="1" value="1" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold md:col-span-2">Mô tả trên card *<textarea name="short_description" rows="3" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></textarea></label>
            <label class="text-xs font-semibold">Giá từ *<input name="base_price" type="number" min="0" step="0.01" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Tiền tệ *<select name="currency" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral">@foreach(\App\Support\MoneyFormatter::SUPPORTED_CURRENCIES as $currency => $label)<option value="{{ $currency }}" @selected($currency === \App\Support\MoneyFormatter::DEFAULT_CURRENCY)>{{ $label }}</option>@endforeach</select></label>
            <div class="md:col-span-2">@include('admin.tours.service-picker', ['selectedServiceIds' => []])</div>
            <div class="md:col-span-2">@include('admin.tours.product-picker', ['selectedProductIds' => []])</div>
            <label class="text-xs font-semibold md:col-span-2">Ảnh tour *<span class="mt-2 grid gap-3 sm:grid-cols-[7rem_1fr] sm:items-center"><span data-image-preview class="grid aspect-[4/3] place-items-center overflow-hidden bg-sand text-[10px] font-semibold text-ink/40">Xem trước</span><input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="block w-full cursor-pointer border border-dashed border-ink/20 bg-cream p-3 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-ink file:px-3 file:py-2 file:text-white"></span></label>
            <button class="accent-contrast rounded-full bg-coral px-6 py-3 text-sm font-semibold md:col-span-2 md:justify-self-start">Thêm tour</button>
        </form>
    </div>
</article>

<div id="edit-tour-{{ $tour->id }}" data-visual-editor-panel hidden class="absolute inset-0 z-[60] overflow-y-auto bg-white/95 p-6 text-left text-ink backdrop-blur-md md:p-9">
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.landing-editor.tours.update', $tour) }}">
        @csrf
        @method('PUT')
        <div class="flex items-start justify-between gap-5 border-b border-ink/10 pb-5"><div><h2 class="text-2xl font-semibold tracking-[-0.04em]">Sửa trực tiếp tour</h2><p class="mt-2 text-xs text-ink/45">Các trường có dấu * bắt buộc nhập.</p></div><button data-visual-editor-close type="button" class="rounded-full border border-ink/15 px-4 py-2 text-xs font-semibold">Đóng</button></div>
        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <label class="text-xs font-semibold md:col-span-2">Tên tour *<input name="name" value="{{ $tour->name }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Điểm đến<select name="destination_id" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"><option value="">Chưa chọn</option>@foreach($destinations as $destination)<option value="{{ $destination->id }}" @selected($tour->destination_id === $destination->id)>{{ $destination->name }}</option>@endforeach</select></label>
            <label class="text-xs font-semibold">Số ngày *<input name="duration_days" type="number" min="1" value="{{ $tour->duration_days }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold md:col-span-2">Mô tả trên card *<textarea name="short_description" rows="3" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral">{{ $tour->short_description }}</textarea></label>
            <label class="text-xs font-semibold">Giá từ *<input name="base_price" type="number" min="0" step="0.01" value="{{ $tour->base_price }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Tiền tệ *<select name="currency" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral">@foreach(\App\Support\MoneyFormatter::SUPPORTED_CURRENCIES as $currency => $label)<option value="{{ $currency }}" @selected(($tour->currency ?: \App\Support\MoneyFormatter::DEFAULT_CURRENCY) === $currency)>{{ $label }}</option>@endforeach</select></label>
            @php($selectedServiceIds = $tour->services->pluck('id'))
            <div class="md:col-span-2">@include('admin.tours.service-picker', ['selectedServiceIds' => $selectedServiceIds])</div>
            <div class="md:col-span-2">@include('admin.tours.product-picker', ['selectedProductIds' => $tour->includedProductIdList()])</div>
            <label class="text-xs font-semibold md:col-span-2">Ảnh tour<span class="mt-2 grid gap-3 sm:grid-cols-[7rem_1fr] sm:items-center"><span data-image-preview class="aspect-[4/3] overflow-hidden bg-sand"><img src="{{ $tour->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($tour->slug).'/1200/900' }}" alt="{{ $tour->name }}" class="h-full w-full object-cover"></span><input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="block w-full cursor-pointer border border-dashed border-ink/20 bg-cream p-3 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-ink file:px-3 file:py-2 file:text-white"></span></label>
        </div>
        <button class="accent-contrast mt-6 rounded-full bg-coral px-6 py-3 text-sm font-semibold">Lưu thay đổi</button>
    </form>
    <form method="post" action="{{ route('admin.landing-editor.tours.destroy', $tour) }}" class="mt-4" onsubmit="return confirm('Xóa tour này?')">@csrf @method('DELETE')<button class="text-sm font-semibold text-red-600">Xóa tour</button></form>
</div>

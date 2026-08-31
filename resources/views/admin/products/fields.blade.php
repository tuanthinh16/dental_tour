<label class="text-sm font-semibold">Tên sản phẩm *
    <input name="name" required value="{{ old('name', $item->name) }}" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">
</label>
<label class="text-sm font-semibold">Slug *
    <input name="slug" required pattern="[A-Za-z0-9_-]+" value="{{ old('slug', $item->slug) }}" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">
</label>
<label class="text-sm font-semibold">Giá bán *
    <input name="base_price" type="number" min="0" step="0.01" required value="{{ old('base_price', $item->base_price ?? 0) }}" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">
</label>
<label class="text-sm font-semibold">Giá gốc
    <input name="original_price" type="number" min="0" step="0.01" value="{{ old('original_price', $item->original_price) }}" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">
</label>
<label class="text-sm font-semibold">Đơn vị tiền tệ *
    <select name="currency" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">
        @foreach(\App\Support\MoneyFormatter::SUPPORTED_CURRENCIES as $currency => $label)
            <option value="{{ $currency }}" @selected(old('currency', $item->currency ?: \App\Support\MoneyFormatter::DEFAULT_CURRENCY) === $currency)>{{ $label }}</option>
        @endforeach
    </select>
</label>
<label class="text-sm font-semibold">Thứ tự *
    <input name="sort_order" type="number" min="0" required value="{{ old('sort_order', $item->sort_order ?? 0) }}" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">
</label>
<label class="text-sm font-semibold md:col-span-2">Mô tả ngắn
    <textarea name="short_description" rows="3" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">{{ old('short_description', $item->short_description) }}</textarea>
</label>
<label class="text-sm font-semibold md:col-span-2">Mô tả chi tiết
    <textarea name="description" rows="5" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-forest">{{ old('description', $item->description) }}</textarea>
</label>
<label class="text-sm font-semibold md:col-span-2">Ảnh sản phẩm
    <span class="mt-3 grid gap-4 md:grid-cols-[10rem_1fr] md:items-center">
        <span data-image-preview class="grid aspect-[4/3] place-items-center overflow-hidden bg-sand text-xs text-ink/40">
            @if($item->image?->file_path)
                <img src="{{ $item->image->file_path }}" alt="Ảnh sản phẩm hiện tại" class="h-full w-full object-cover">
            @else
                Xem trước
            @endif
        </span>
        <input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="block w-full cursor-pointer border border-dashed border-ink/25 bg-cream px-4 py-5 text-sm file:mr-3 file:border-0 file:bg-ink file:px-4 file:py-2 file:text-white">
    </span>
</label>
<label class="flex items-center gap-3 border-t border-ink/10 pt-5 text-sm font-semibold md:col-span-2"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->exists ? $item->is_active : true))> Hiển thị sản phẩm</label>
<div class="flex flex-wrap gap-3 md:col-span-2">
    <button class="accent-contrast bg-coral px-7 py-3 text-sm font-semibold">{{ $submitLabel }}</button>
    @if($item->exists)
        @if(isset($dialogId))
            <button type="button" data-product-dialog-close="{{ $dialogId }}" class="border border-ink/20 px-7 py-3 text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Hủy</button>
        @else
            <a href="{{ route('admin.products.index') }}" class="border border-ink/20 px-7 py-3 text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Hủy</a>
        @endif
    @endif
</div>

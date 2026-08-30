@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div>
        <p class="text-sm font-semibold text-brand-600">Biên tập hành trình</p>
        <h1 class="mt-2 max-w-5xl text-4xl font-semibold tracking-[-0.05em] text-ink md:text-6xl">{{ $title }}</h1>
        <p class="mt-4 max-w-2xl text-sm leading-6 text-ink/55">Xây dựng thông tin, hình ảnh, dịch vụ và lịch trình của tour trong một nơi.</p>
    </div>
    <form method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.tours.update', $item) : route('admin.tours.store') }}" class="mt-10 space-y-5">
        @csrf
        @if($item->exists)
            @method('PUT')
        @endif

        <section class="grid gap-5 bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:grid-cols-2 md:p-9">
            @foreach([
                ['name','Tên tour','text'],
                ['slug','Slug','text'],
                ['duration_days','Số ngày','number'],
                ['duration_nights','Số đêm','number'],
                ['base_price','Giá bán','number'],
                ['original_price','Giá gốc','number'],
                ['badge','Badge','text'],
                ['sort_order','Thứ tự','number'],
            ] as $field)
                <label class="text-sm font-bold">
                    {{ $field[1] }}
                    <input name="{{ $field[0] }}" type="{{ $field[2] }}" @if(in_array($field[0], ['base_price','original_price'])) step="0.01" @endif value="{{ old($field[0], $item->{$field[0]} ?? 0) }}" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none transition-colors focus:border-brand-500">
                </label>
            @endforeach

            <label class="text-sm font-bold">
                Đơn vị tiền tệ
                <select name="currency" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-brand-500">
                    @foreach(\App\Support\MoneyFormatter::SUPPORTED_CURRENCIES as $currency => $label)
                        <option value="{{ $currency }}" @selected(old('currency', $item->currency ?: \App\Support\MoneyFormatter::DEFAULT_CURRENCY) === $currency)>{{ $label }}</option>
                    @endforeach
                </select>
            </label>

            <label class="text-sm font-bold">
                Điểm đến
                <select name="destination_id" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-brand-500">
                    <option value="">-- Chọn --</option>
                    @foreach($destinations as $destination)
                        <option value="{{ $destination->id }}" @selected(old('destination_id', $item->destination_id) == $destination->id)>{{ $destination->name }}</option>
                    @endforeach
                </select>
            </label>
            <label class="text-sm font-bold md:col-span-2">
                Mô tả ngắn
                <textarea name="short_description" rows="3" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-brand-500">{{ old('short_description', $item->short_description) }}</textarea>
            </label>
            <label class="text-sm font-bold md:col-span-2">
                Mô tả chi tiết
                <textarea name="description" rows="8" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none focus:border-brand-500">{{ old('description', $item->description) }}</textarea>
            </label>
            <label class="text-sm font-bold md:col-span-2">
                Ảnh tour
                <span class="mt-2 block text-xs font-normal leading-5 text-slate-400">JPG, PNG hoặc WebP, tối đa 5 MB. Không chọn ảnh mới để giữ ảnh hiện tại.</span>
                <span class="mt-4 grid gap-4 md:grid-cols-[14rem_1fr] md:items-center">
                    <span data-image-preview class="relative aspect-[4/3] overflow-hidden bg-sand">
                        @if($item->image?->file_path)
                            <img src="{{ $item->image->file_path }}" alt="Ảnh tour hiện tại" class="h-full w-full object-cover">
                        @else
                            <span class="grid h-full place-items-center text-xs font-semibold text-ink/40">Chưa có ảnh</span>
                        @endif
                    </span>
                    <input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="block w-full cursor-pointer border border-dashed border-ink/25 bg-cream px-4 py-5 text-sm text-ink file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white">
                </span>
            </label>
            <label class="flex items-center gap-3 font-bold"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $item->is_featured))> Nổi bật</label>
            <label class="flex items-center gap-3 font-bold"><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $item->exists ? $item->is_active : true))> Hoạt động</label>
        </section>

        @php($selectedServiceIds = collect(old('service_ids', $item->services?->pluck('id')->all() ?? []))->map(fn ($id) => (int) $id))
        <section class="bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
            <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-end">
                <div>
                    <h2 class="text-xl font-black">Dịch vụ đi kèm</h2>
                    <p class="mt-2 text-sm text-slate-500">Chọn nhiều dịch vụ từ danh mục dùng chung cho tour này.</p>
                </div>
                <a href="{{ route('admin.included-services.index') }}" class="text-sm font-bold text-brand-600">Quản lý danh mục</a>
            </div>
            <input type="hidden" name="service_selection_submitted" value="1">
            <details data-service-picker class="relative mt-5 max-w-2xl">
                <summary class="flex cursor-pointer list-none items-center justify-between rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-semibold text-slate-700">
                    <span data-service-summary>{{ $selectedServiceIds->count() ? $selectedServiceIds->count().' dịch vụ đã chọn' : 'Chọn dịch vụ' }}</span>
                    <svg viewBox="0 0 20 20" class="size-4 fill-none stroke-current" stroke-width="2" aria-hidden="true"><path d="m5 7.5 5 5 5-5" /></svg>
                </summary>
                <div class="absolute z-20 mt-2 max-h-72 w-full overflow-y-auto rounded-xl border border-slate-200 bg-white p-2 shadow-2xl">
                    @forelse($services as $service)
                        <label class="flex cursor-pointer items-start gap-3 rounded-lg px-3 py-3 transition-colors hover:bg-slate-50">
                            <input data-service-option type="checkbox" name="service_ids[]" value="{{ $service->id }}" @checked($selectedServiceIds->contains($service->id)) class="mt-0.5 size-4 rounded border-slate-300 text-brand-600">
                            <span>
                                <span class="block text-sm font-semibold text-slate-800">{{ $service->name }}</span>
                                @if($service->description)
                                    <span class="mt-1 block text-xs leading-5 text-slate-400">{{ $service->description }}</span>
                                @endif
                            </span>
                        </label>
                    @empty
                        <p class="px-3 py-5 text-sm text-slate-400">Chưa có dịch vụ. Hãy thêm dịch vụ trong danh mục trước.</p>
                    @endforelse
                </div>
            </details>
        </section>

        <section class="bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
            @include('admin.tours.product-picker', [
                'selectedProductIds' => old('included_product_ids', $item->includedProductIdList()),
            ])
        </section>

        @php($itineraries = old('itineraries', $item->itineraries?->map(fn ($row) => \Illuminate\Support\Arr::only((array) $row, ['day_number','title','description']))->all() ?? []))
        <section class="bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
            <div class="flex justify-between"><h2 class="text-xl font-black">Lịch trình</h2><button type="button" onclick="addItinerary()" class="font-bold text-brand-600">+ Thêm ngày</button></div>
            <div id="itineraries" class="mt-5 space-y-4">
                @foreach($itineraries as $i => $row)
                    <div class="grid gap-3 rounded-xl bg-slate-50 p-4 md:grid-cols-[90px_1fr_2fr_auto]">
                        <input name="itineraries[{{ $i }}][day_number]" type="number" min="1" value="{{ $row['day_number'] }}" class="rounded-lg border p-3" placeholder="Ngày">
                        <input name="itineraries[{{ $i }}][title]" value="{{ $row['title'] }}" class="rounded-lg border p-3" placeholder="Tiêu đề">
                        <textarea name="itineraries[{{ $i }}][description]" class="rounded-lg border p-3" placeholder="Nội dung">{{ $row['description'] }}</textarea>
                        <button type="button" onclick="this.parentElement.remove()" class="text-red-600">Xóa</button>
                    </div>
                @endforeach
            </div>
        </section>

        @php($excludedItems = old('excluded_items', $item->excludedItems?->map(fn ($row) => \Illuminate\Support\Arr::only((array) $row, ['content']))->all() ?? []))
        <section class="bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
            <div class="flex justify-between"><h2 class="text-xl font-black">Không bao gồm</h2><button type="button" onclick="addItem('excluded_items')" class="font-bold text-brand-600">+ Thêm</button></div>
            <div id="excluded_items" class="mt-5 space-y-3">
                @foreach($excludedItems as $i => $row)
                    <div class="flex gap-3"><input class="flex-1 rounded-lg border p-3" name="excluded_items[{{ $i }}][content]" value="{{ $row['content'] }}"><button type="button" onclick="this.parentElement.remove()" class="text-red-600">Xóa</button></div>
                @endforeach
            </div>
        </section>

        <div class="flex flex-wrap gap-3 pt-3"><button class="accent-contrast rounded-full bg-coral px-7 py-3 text-sm font-semibold transition-transform hover:scale-105">Lưu tour</button><a href="{{ route('admin.tours.index') }}" class="rounded-full border border-ink/20 px-7 py-3 text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Hủy</a></div>
    </form>

    <script>
        function addItinerary() {
            const box = document.getElementById('itineraries');
            const index = box.children.length;
            box.insertAdjacentHTML('beforeend', `<div class="grid gap-3 rounded-xl bg-slate-50 p-4 md:grid-cols-[90px_1fr_2fr_auto]"><input name="itineraries[${index}][day_number]" type="number" min="1" value="${index + 1}" class="rounded-lg border p-3"><input name="itineraries[${index}][title]" class="rounded-lg border p-3" placeholder="Tiêu đề"><textarea name="itineraries[${index}][description]" class="rounded-lg border p-3" placeholder="Nội dung"></textarea><button type="button" onclick="this.parentElement.remove()" class="text-red-600">Xóa</button></div>`);
        }

        function addItem(key) {
            const box = document.getElementById(key);
            const index = box.children.length;
            box.insertAdjacentHTML('beforeend', `<div class="flex gap-3"><input class="flex-1 rounded-lg border p-3" name="${key}[${index}][content]"><button type="button" onclick="this.parentElement.remove()" class="text-red-600">Xóa</button></div>`);
        }
    </script>
@endsection

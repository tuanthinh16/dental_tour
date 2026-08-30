@extends('layouts.admin')

@section('title', $title)

@section('content')
    <div class="max-w-5xl">
        <p class="text-sm font-semibold text-brand-600">Quản trị nội dung</p>
        <h1 class="mt-2 text-4xl font-semibold tracking-[-0.04em] text-ink md:text-5xl">{{ $title }}</h1>
        <p class="mt-4 max-w-2xl text-sm leading-6 text-ink/55">Nhập và lưu dữ liệu trực tiếp từ đây. Ảnh sản phẩm được lưu trong bảng product_image và liên kết bằng product_code.</p>
    </div>

    <form method="post" enctype="multipart/form-data" action="{{ $item->exists ? route('admin.'.$route.'.update', $item) : route('admin.'.$route.'.store') }}" class="mt-10 max-w-5xl bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
        @csrf
        @if($item->exists)
            @method('PUT')
        @endif

        <div class="grid gap-6 md:grid-cols-2">
            @foreach($fields as $name => $field)
                @php($value = old($name, $item->$name ?? ($field['default'] ?? '')))
                @php($required = $field['required'] ?? false)
                @if($field['type'] === 'checkbox')
                    <label class="flex items-center gap-3 border-t border-ink/10 pt-5 font-semibold text-ink">
                        <input type="checkbox" name="{{ $name }}" value="1" @checked($value) class="size-5 rounded border-ink/25 text-brand-600">
                        {{ $field['label'] }}
                    </label>
                @elseif($field['type'] === 'image')
                    <label class="md:col-span-2">
                        <span class="text-sm font-semibold text-ink">{{ $field['label'] }}</span>
                        <span class="mt-2 block text-xs leading-5 text-ink/45">JPG, PNG hoặc WebP, tối đa 5 MB. Nếu không chọn ảnh mới, ảnh hiện tại được giữ nguyên.</span>
                        <span class="mt-4 grid gap-4 md:grid-cols-[12rem_1fr] md:items-center">
                            <span data-image-preview class="relative aspect-[4/3] overflow-hidden bg-sand">
                                @if($item->image?->file_path)
                                    <img src="{{ $item->image->file_path }}" alt="Ảnh hiện tại" class="h-full w-full object-cover">
                                @else
                                    <span class="grid h-full place-items-center text-xs font-semibold text-ink/40">Chưa có ảnh</span>
                                @endif
                            </span>
                            <span>
                            <input data-image-input type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp" @required($required && !$item->exists) class="block w-full cursor-pointer border border-dashed border-ink/25 bg-cream px-4 py-5 text-sm text-ink file:mr-4 file:rounded-full file:border-0 file:bg-ink file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:border-brand-500">
                            </span>
                        </span>
                    </label>
                @else
                    <label @class(['md:col-span-2' => $field['type'] === 'textarea'])>
                        <span class="text-sm font-semibold text-ink">{{ $field['label'] }}</span>
                        @if($field['type'] === 'textarea')
                            <textarea name="{{ $name }}" rows="{{ $name === 'content' || $name === 'description' ? 8 : 3 }}" @required($required) class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none transition-colors focus:border-brand-500">{{ $value }}</textarea>
                        @else
                            <input type="{{ $field['type'] }}" name="{{ $name }}" value="{{ $value }}" @required($required) class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 font-normal outline-none transition-colors focus:border-brand-500">
                        @endif
                    </label>
                @endif
            @endforeach
        </div>

        <div class="mt-10 flex flex-wrap gap-3 border-t border-ink/10 pt-6">
            <button class="rounded-full bg-ink px-7 py-3 text-sm font-semibold text-white transition-transform hover:scale-105">Lưu dữ liệu</button>
            <a href="{{ route('admin.'.$route.'.index') }}" class="rounded-full border border-ink/20 px-7 py-3 text-sm font-semibold text-ink transition-colors hover:bg-ink hover:text-white">Hủy</a>
        </div>
    </form>
@endsection

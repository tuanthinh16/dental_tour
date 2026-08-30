<section id="create-{{ $route }}" class="mt-8 scroll-mt-28 bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
    <div class="flex flex-col justify-between gap-3 border-b border-ink/10 pb-6 sm:flex-row sm:items-end">
        <div><p class="text-sm font-semibold text-brand-600">Thêm trực tiếp</p><h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-ink">{{ $createLabel }}</h2></div>
        <p class="text-xs text-ink/45">Các trường có dấu * bắt buộc nhập.</p>
    </div>
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.'.$route.'.store') }}" class="mt-7">
        @csrf
        <div class="grid gap-5 md:grid-cols-2">
            @foreach($createFields as $name => $field)
                @php($value = old($name, $field['default'] ?? ''))
                @php($required = $field['required'] ?? false)
                @if($field['type'] === 'checkbox')
                    <label class="flex items-center gap-3 border-t border-ink/10 pt-5 font-semibold text-ink"><input type="checkbox" name="{{ $name }}" value="1" @checked($value) class="size-5 border-ink/25 text-brand-600">{{ $field['label'] }}</label>
                @elseif($field['type'] === 'image')
                    <label class="md:col-span-2"><span class="text-sm font-semibold text-ink">{{ $field['label'] }}{{ $required ? ' *' : '' }}</span><span class="mt-3 grid gap-4 md:grid-cols-[10rem_1fr] md:items-center"><span data-image-preview class="grid aspect-[4/3] place-items-center overflow-hidden bg-sand text-xs font-semibold text-ink/40">Xem trước</span><input data-image-input type="file" name="{{ $name }}" accept="image/jpeg,image/png,image/webp" @required($required) class="block w-full cursor-pointer border border-dashed border-ink/25 bg-cream px-4 py-5 text-sm file:mr-3 file:rounded-full file:border-0 file:bg-ink file:px-4 file:py-2 file:text-white"></span></label>
                @else
                    <label @class(['md:col-span-2' => $field['type'] === 'textarea'])><span class="text-sm font-semibold text-ink">{{ $field['label'] }}{{ $required ? ' *' : '' }}</span>@if($field['type'] === 'textarea')<textarea name="{{ $name }}" rows="{{ $name === 'content' || $name === 'description' ? 6 : 3 }}" @required($required) class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 outline-none focus:border-brand-500">{{ $value }}</textarea>@else<input type="{{ $field['type'] }}" name="{{ $name }}" value="{{ $value }}" @required($required) class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 outline-none focus:border-brand-500">@endif</label>
                @endif
            @endforeach
        </div>
        <button class="accent-contrast mt-7 rounded-full bg-coral px-7 py-3 text-sm font-semibold">{{ $createLabel }}</button>
    </form>
</section>

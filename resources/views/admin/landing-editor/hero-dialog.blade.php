<div id="edit-hero" data-visual-editor-panel hidden class="absolute bottom-8 right-5 top-28 z-40 w-[calc(100%-2.5rem)] max-w-xl overflow-y-auto bg-white p-6 text-left text-ink shadow-[0_30px_90px_rgba(5,24,20,0.32)] md:right-10 md:p-8">
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.landing-editor.hero-image') }}">
        @csrf
        @method('PUT')
        <div class="flex items-start justify-between gap-5 border-b border-ink/10 pb-5">
            <div><h2 class="text-2xl font-semibold tracking-[-0.04em]">Sửa trực tiếp hero</h2><p class="mt-2 text-xs text-ink/45">{{ app()->getLocale() === 'en' ? __('admin.translation_notice') : 'Các trường có dấu * bắt buộc nhập.' }}</p></div>
            <button data-visual-editor-close type="button" class="rounded-full border border-ink/15 px-4 py-2 text-xs font-semibold">Đóng</button>
        </div>
        <div class="mt-6 grid gap-4">
            <label class="text-xs font-semibold">Dòng giới thiệu *<input name="eyebrow" value="{{ $heroEyebrow }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Tiêu đề dòng 1 *<input name="title_line_1" value="{{ $heroTitleLine1 }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <div class="grid gap-3 sm:grid-cols-2">
                <label class="text-xs font-semibold">Trước ảnh *<input name="title_before_image" value="{{ $heroTitleBeforeImage }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
                <label class="text-xs font-semibold">Sau ảnh *<input name="title_after_image" value="{{ $heroTitleAfterImage }}" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            </div>
            <label class="text-xs font-semibold">Mô tả *<textarea name="description" rows="3" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral">{{ $heroDescription }}</textarea></label>
            @if(app()->getLocale() === 'vi')
                <label class="text-xs font-semibold">Ảnh nền<span class="mt-2 grid gap-3 sm:grid-cols-[7rem_1fr] sm:items-center"><span data-image-preview class="aspect-[4/3] overflow-hidden bg-sand"><img src="{{ $heroImage }}" alt="Ảnh nền hero" class="h-full w-full object-cover"></span><input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="block w-full cursor-pointer border border-dashed border-ink/20 bg-cream p-3 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-ink file:px-3 file:py-2 file:text-white"></span></label>
            @endif
        </div>
        <button class="accent-contrast mt-6 rounded-full bg-coral px-6 py-3 text-sm font-semibold">Lưu hero</button>
    </form>
</div>

<article class="group relative mt-6 min-h-[18rem] overflow-hidden border border-dashed border-ink/25 bg-ink text-white shadow-[0_20px_60px_rgba(5,24,20,0.08)]">
    <div class="hero-wash absolute inset-0 opacity-60"></div>
    <button data-visual-editor-open="create-destination-inline" type="button" aria-expanded="false" class="absolute inset-0 z-10 flex w-full flex-col items-center justify-center p-7 text-center transition-colors duration-500 hover:bg-white/5">
        <span class="accent-contrast grid size-14 place-items-center rounded-full bg-coral text-3xl shadow-xl transition-transform duration-500 group-hover:scale-110">+</span>
        <span class="mt-5 text-2xl font-semibold tracking-[-0.04em]">Thêm điểm đến</span>
        <span class="mt-2 text-sm text-white/55">Tạo block mới ở cuối thứ tự ưu tiên.</span>
    </button>
    <div id="create-destination-inline" data-visual-editor-panel hidden class="absolute inset-0 z-20 overflow-y-auto bg-white p-6 text-left text-ink md:p-8">
        <div class="flex items-start justify-between gap-4 border-b border-ink/10 pb-5"><div><p class="text-sm font-semibold text-coral">Điểm đến mới</p><h3 class="mt-2 text-2xl font-semibold tracking-[-0.04em]">Nhập nội dung block.</h3><p class="mt-2 text-xs text-ink/45">Tên và ảnh là bắt buộc.</p></div><button data-visual-editor-close type="button" class="rounded-full border border-ink/15 px-4 py-2 text-xs font-semibold">Đóng</button></div>
        <form method="post" enctype="multipart/form-data" action="{{ route('admin.landing-editor.destinations.store') }}" class="mt-6 grid gap-4 md:grid-cols-2">
            @csrf
            <label class="text-xs font-semibold">Tên điểm đến *<input name="name" required class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Ảnh *<span class="mt-2 grid gap-3 sm:grid-cols-[6rem_1fr] sm:items-center"><span data-image-preview class="grid aspect-[4/3] place-items-center overflow-hidden bg-sand text-[10px] font-semibold text-ink/40">Xem trước</span><input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" required class="block w-full cursor-pointer border border-dashed border-ink/20 bg-cream p-3 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-ink file:px-3 file:py-2 file:text-white"></span></label>
            <label class="text-xs font-semibold md:col-span-2">Mô tả<textarea name="short_description" rows="3" class="mt-2 w-full border border-ink/15 bg-cream px-4 py-3 text-sm font-normal outline-none focus:border-coral"></textarea></label>
            <button class="accent-contrast rounded-full bg-coral px-6 py-3 text-sm font-semibold md:col-span-2 md:justify-self-start">Thêm điểm đến</button>
        </form>
    </div>
</article>

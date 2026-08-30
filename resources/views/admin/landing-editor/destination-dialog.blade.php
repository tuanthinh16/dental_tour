@php($editorPanelId = $panelId ?? 'edit-destination-'.$destination->id)
<div id="{{ $editorPanelId }}" data-visual-editor-panel hidden class="absolute inset-0 z-40 overflow-y-auto bg-white/95 p-5 text-left text-ink backdrop-blur-md md:p-7">
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.landing-editor.destinations.update', $destination) }}">
        @csrf
        @method('PUT')
        <div class="flex items-center justify-between gap-4 border-b border-ink/10 pb-4"><h3 class="text-xl font-semibold tracking-[-0.04em]">Sửa điểm đến</h3><button data-visual-editor-close type="button" class="rounded-full border border-ink/15 px-3 py-2 text-xs font-semibold">Đóng</button></div>
        <div class="mt-4 grid gap-4">
            <label class="text-xs font-semibold">Tên điểm đến *<input name="name" value="{{ $destination->name }}" required class="mt-2 w-full border border-ink/15 bg-cream px-3 py-2 text-sm font-normal outline-none focus:border-coral"></label>
            <label class="text-xs font-semibold">Mô tả<textarea name="short_description" rows="3" class="mt-2 w-full border border-ink/15 bg-cream px-3 py-2 text-sm font-normal outline-none focus:border-coral">{{ $destination->short_description }}</textarea></label>
            <label class="text-xs font-semibold">Ảnh<span class="mt-2 grid gap-3 sm:grid-cols-[6rem_1fr] sm:items-center"><span data-image-preview class="aspect-[4/3] overflow-hidden bg-sand"><img src="{{ $destination->image?->file_path ?: 'https://picsum.photos/seed/'.urlencode($destination->slug).'/1200/900' }}" alt="{{ $destination->name }}" class="h-full w-full object-cover"></span><input data-image-input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="block w-full cursor-pointer border border-dashed border-ink/20 bg-cream p-3 text-xs file:mr-2 file:rounded-full file:border-0 file:bg-ink file:px-3 file:py-2 file:text-white"></span></label>
        </div>
        <button class="accent-contrast mt-5 rounded-full bg-coral px-5 py-2.5 text-xs font-semibold">Lưu thay đổi</button>
    </form>
    <form method="post" action="{{ route('admin.landing-editor.destinations.destroy', $destination) }}" class="mt-3" onsubmit="return confirm('Xóa điểm đến này? Các tour liên quan sẽ không còn điểm đến.')">@csrf @method('DELETE')<button class="text-xs font-semibold text-red-600">Xóa điểm đến</button></form>
</div>

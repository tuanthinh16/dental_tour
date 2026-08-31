<section id="create-products" class="mt-8 scroll-mt-28 bg-white p-6 shadow-[0_24px_70px_rgba(5,24,20,0.1)] md:p-9">
    <div class="flex flex-col justify-between gap-3 border-b border-ink/10 pb-6 sm:flex-row sm:items-end">
        <div><p class="text-sm font-semibold text-forest">Thêm trực tiếp</p><h2 class="mt-2 text-3xl font-semibold tracking-[-0.04em] text-ink">Sản phẩm mới</h2></div>
        <p class="text-xs text-ink/45">Các trường có dấu * bắt buộc nhập.</p>
    </div>
    <form method="post" enctype="multipart/form-data" action="{{ route('admin.products.store') }}" class="mt-7 grid gap-5 md:grid-cols-2">
        @csrf
        @include('admin.products.fields', ['item' => new \App\Models\Product, 'submitLabel' => 'Thêm sản phẩm'])
    </form>
</section>

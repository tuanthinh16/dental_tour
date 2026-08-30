@php($selectedProductIds = collect($selectedProductIds ?? [])->map(fn ($id) => (int) $id))
<div>
    <p class="text-xs font-semibold">Sản phẩm mua thêm</p>
    <input type="hidden" name="included_product_selection_submitted" value="1">
    <details data-product-picker class="relative mt-2">
        <summary class="flex cursor-pointer list-none items-center justify-between border border-ink/15 bg-cream px-4 py-3 text-sm font-semibold text-ink">
            <span data-product-summary>{{ $selectedProductIds->count() ? $selectedProductIds->count().' sản phẩm đã chọn' : 'Chọn sản phẩm' }}</span>
            <svg viewBox="0 0 20 20" class="size-4 shrink-0 fill-none stroke-current" stroke-width="2" aria-hidden="true"><path d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="max-h-64 overflow-y-auto border-x border-b border-ink/15 bg-white p-2 shadow-xl">
            @forelse($addonProducts as $product)
                <label class="flex cursor-pointer items-start justify-between gap-3 px-3 py-3 transition-colors hover:bg-cream">
                    <span class="flex min-w-0 items-start gap-3">
                        <input data-product-option type="checkbox" name="included_product_ids[]" value="{{ $product->id }}" @checked($selectedProductIds->contains($product->id)) class="mt-0.5 size-4 shrink-0 border-ink/25 text-forest">
                        <span class="truncate text-sm font-semibold text-ink">{{ $product->name }}</span>
                    </span>
                    <span class="shrink-0 text-xs text-ink/45">{{ \App\Support\MoneyFormatter::format($product->daily_price, $product->currency) }}</span>
                </label>
            @empty
                <p class="px-3 py-5 text-sm text-ink/45">Chưa có sản phẩm mua thêm.</p>
            @endforelse
        </div>
    </details>
</div>

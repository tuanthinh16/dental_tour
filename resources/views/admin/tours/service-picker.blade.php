@php($selectedServiceIds = collect($selectedServiceIds ?? [])->map(fn ($id) => (int) $id))
<div>
    <p class="text-xs font-semibold">Dịch vụ đi kèm</p>
    <input type="hidden" name="service_selection_submitted" value="1">
    <details data-service-picker class="relative mt-2">
        <summary class="flex cursor-pointer list-none items-center justify-between border border-ink/15 bg-cream px-4 py-3 text-sm font-semibold text-ink">
            <span data-service-summary>{{ $selectedServiceIds->count() ? $selectedServiceIds->count().' dịch vụ đã chọn' : 'Chọn dịch vụ' }}</span>
            <svg viewBox="0 0 20 20" class="size-4 shrink-0 fill-none stroke-current transition-transform group-open:rotate-180" stroke-width="2" aria-hidden="true"><path d="m5 7.5 5 5 5-5" /></svg>
        </summary>
        <div class="max-h-64 overflow-y-auto border-x border-b border-ink/15 bg-white p-2 shadow-xl">
            @forelse($services as $service)
                <label class="flex cursor-pointer items-start gap-3 px-3 py-3 transition-colors hover:bg-cream">
                    <input data-service-option type="checkbox" name="service_ids[]" value="{{ $service->id }}" @checked($selectedServiceIds->contains($service->id)) class="mt-0.5 size-4 shrink-0 border-ink/25 text-forest">
                    <span class="min-w-0">
                        <span class="block truncate text-sm font-semibold text-ink">{{ $service->name }}</span>
                        @if($service->description)
                            <span class="mt-1 block text-xs leading-5 text-ink/45">{{ $service->description }}</span>
                        @endif
                    </span>
                </label>
            @empty
                <p class="px-3 py-5 text-sm text-ink/45">Chưa có dịch vụ trong danh mục.</p>
            @endforelse
        </div>
    </details>
</div>

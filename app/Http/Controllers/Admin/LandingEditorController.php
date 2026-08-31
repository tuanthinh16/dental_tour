<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LandingDestinationRequest;
use App\Http\Requests\Admin\LandingHeroImageRequest;
use App\Http\Requests\Admin\LandingTourRequest;
use App\Models\Destination;
use App\Models\IncludedService;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tour;
use App\Repositories\DestinationRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TourRepository;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LandingEditorController extends Controller
{
    public function __construct(
        private DestinationRepository $destinations,
        private TourRepository $tours,
        private SettingRepository $settings,
        private MediaService $media,
    ) {}

    public function index()
    {
        return view('home', [
            'destinations' => $this->destinations->active()->take(5),
            'featuredTours' => $this->tours->featured(50),
            'tours' => $this->tours->activePaginated(8),
            'settings' => $this->settings->values(),
            'services' => IncludedService::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'addonProducts' => Product::query()
                ->where('product_type', 'addon')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'editorMode' => true,
        ]);
    }

    public function updateHeroImage(LandingHeroImageRequest $request)
    {
        $suffix = app()->getLocale() === 'vi' ? '' : '_'.app()->getLocale();
        $settingValues = [
            'landing_hero_eyebrow'.$suffix => $request->validated('eyebrow'),
            'landing_hero_title_line_1'.$suffix => $request->validated('title_line_1'),
            'landing_hero_title_before_image'.$suffix => $request->validated('title_before_image'),
            'landing_hero_title_after_image'.$suffix => $request->validated('title_after_image'),
            'landing_hero_description'.$suffix => $request->validated('description'),
        ];

        if (app()->getLocale() === 'vi' && $request->hasFile('image')) {
            $settingValues['landing_hero_image'] = $this->media
                ->uploadSiteImage($request->file('image'), 'hero');
        }

        foreach ($settingValues as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        Log::info('Admin landing hero updated inline', ['fields' => array_keys($settingValues)]);

        return redirect()
            ->to(route('admin.landing-editor').'#landing-hero')
            ->with('success', 'Đã cập nhật nội dung hero.');
    }

    public function updateDestination(LandingDestinationRequest $request, Destination $destination)
    {
        if (app()->getLocale() !== 'vi') {
            $destination->replaceTranslation(app()->getLocale(), [
                'short_description' => $request->validated('short_description'),
            ]);

            return redirect()
                ->to(route('admin.landing-editor').'#destinations')
                ->with('success', 'Đã cập nhật bản dịch điểm đến.');
        }

        $data = $request->safe()->except('image');
        $destination->update($data);
        if ($request->hasFile('image')) {
            $this->media->uploadProductImage(
                $request->file('image'),
                $destination->product_code,
                'list',
                $destination->name,
            );
        }
        Log::info('Admin landing destination updated inline', ['destination_id' => $destination->id]);

        return redirect()
            ->to(route('admin.landing-editor').'#destinations')
            ->with('success', 'Đã cập nhật nội dung điểm đến.');
    }

    public function storeDestination(LandingDestinationRequest $request)
    {
        abort_if(app()->getLocale() !== 'vi', 403);
        $data = $request->safe()->except('image');
        $data += [
            'slug' => $this->uniqueDestinationSlug($data['name']),
            'product_code' => Product::makeUniqueCode($data['name'], 'DEST'),
            'description' => $data['short_description'] ?? null,
            'sort_order' => (int) Destination::query()->max('sort_order') + 1,
            'is_active' => true,
        ];
        $destination = Destination::create($data);
        $this->media->uploadProductImage(
            $request->file('image'),
            $destination->product_code,
            'list',
            $destination->name,
        );
        Log::info('Admin landing destination created inline', ['destination_id' => $destination->id]);

        return redirect()
            ->to(route('admin.landing-editor').'#destination-priority')
            ->with('success', 'Đã thêm điểm đến mới.');
    }

    public function destroyDestination(Destination $destination)
    {
        abort_if(app()->getLocale() !== 'vi', 403);
        $destination->delete();
        Log::info('Admin landing destination deleted inline', ['destination_id' => $destination->id]);

        return redirect()
            ->to(route('admin.landing-editor').'#destinations')
            ->with('success', 'Đã xóa điểm đến.');
    }

    public function storeTour(LandingTourRequest $request)
    {
        abort_if(app()->getLocale() !== 'vi', 403);
        $tour = DB::transaction(function () use ($request): Tour {
            $data = $request->safe()->except(['image', 'service_ids', 'included_product_ids']);
            $data += [
                'slug' => $this->uniqueTourSlug($data['name']),
                'product_code' => Product::makeUniqueCode($data['name'], 'TOUR'),
                'description' => $data['short_description'],
                'duration_nights' => max(0, $data['duration_days'] - 1),
                'is_featured' => true,
                'is_active' => true,
                'sort_order' => (int) Tour::query()->max('sort_order') + 1,
            ];
            $tour = Tour::create($data);
            $this->media->uploadProductImage(
                $request->file('image'),
                $tour->product_code,
                'list',
                $tour->name,
            );
            $tour->syncCategoryIds($request->validated('service_ids', []));
            $tour->syncIncludedProductIds($request->validated('included_product_ids', []));

            return $tour;
        });
        Log::info('Admin landing tour created inline', ['tour_id' => $tour->id]);

        return redirect()
            ->to(route('admin.landing-editor').'#featured-tours')
            ->with('success', 'Đã thêm tour mới.');
    }

    public function updateTour(LandingTourRequest $request, Tour $tour)
    {
        if (app()->getLocale() !== 'vi') {
            $tour->replaceTranslation(app()->getLocale(), $request->safe()->only([
                'name',
                'short_description',
                'description',
            ]));

            return redirect()
                ->to(route('admin.landing-editor').'#featured-tours')
                ->with('success', 'Đã cập nhật bản dịch tour.');
        }

        DB::transaction(function () use ($request, $tour): void {
            $data = $request->safe()->except(['image', 'service_ids', 'included_product_ids']);
            $data['duration_nights'] = max(0, $data['duration_days'] - 1);
            if ($request->hasFile('image')) {
                $this->media->uploadProductImage(
                    $request->file('image'),
                    $tour->product_code,
                    'list',
                    $data['name'],
                );
            }
            $tour->update($data);
            $tour->syncCategoryIds($request->validated('service_ids', []));
            $tour->syncIncludedProductIds($request->validated('included_product_ids', []));
        });
        Log::info('Admin landing tour updated inline', ['tour_id' => $tour->id]);

        return redirect()
            ->to(route('admin.landing-editor').'#featured-tours')
            ->with('success', 'Đã cập nhật nội dung tour.');
    }

    public function destroyTour(Tour $tour)
    {
        abort_if(app()->getLocale() !== 'vi', 403);
        $tour->delete();
        Log::info('Admin landing tour deleted inline', ['tour_id' => $tour->id]);

        return redirect()
            ->to(route('admin.landing-editor').'#featured-tours')
            ->with('success', 'Đã xóa tour.');
    }

    public function reorderDestinations(Request $request)
    {
        $data = $request->validate([
            'destination_ids' => ['required', 'array', 'min:1'],
            'destination_ids.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')
                    ->where('product_type', 'destination')
                    ->whereNull('deleted_at'),
            ],
        ]);

        DB::transaction(function () use ($data): void {
            foreach ($data['destination_ids'] as $sortOrder => $destinationId) {
                Destination::whereKey($destinationId)->update(['sort_order' => $sortOrder]);
            }
        });

        return response()->json(['message' => 'Đã cập nhật thứ tự điểm đến.']);
    }

    private function uniqueTourSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'tour';
        $slug = $base;
        $suffix = 2;

        while (Tour::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }

    private function uniqueDestinationSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'diem-den';
        $slug = $base;
        $suffix = 2;

        while (Destination::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$suffix++;
        }

        return $slug;
    }
}

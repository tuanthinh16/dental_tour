<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Models\Destination;
use App\Models\IncludedService;
use App\Models\Page;
use App\Models\Product;
use App\Models\Tour;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $leadCounts = ConsultationRequest::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('admin.dashboard', [
            'tourCount' => Tour::count(),
            'activeTourCount' => Tour::where('is_active', true)->count(),
            'featuredTourCount' => Tour::where('is_featured', true)->count(),
            'destinationCount' => Destination::count(),
            'activeDestinationCount' => Destination::where('is_active', true)->count(),
            'productCount' => Product::where('product_type', 'addon')->count(),
            'activeProductCount' => Product::where('product_type', 'addon')->where('is_active', true)->count(),
            'serviceCount' => IncludedService::count(),
            'activePageCount' => Page::where('is_active', true)->count(),
            'leadCounts' => $leadCounts,
            'latestConsultations' => ConsultationRequest::with('tour:id,name')
                ->latest()
                ->limit(6)
                ->get(),
            'leadStatusLabels' => [
                'new' => 'Mới',
                'contacted' => 'Đã liên hệ',
                'completed' => 'Hoàn tất',
                'cancelled' => 'Đã hủy',
            ],
        ]);
    }
}

<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ConsultationRequest;
use App\Models\Destination;
use App\Models\Tour;
class DashboardController extends Controller
{
    public function __invoke()
    {
        return view("admin.dashboard", [
            "tourCount" => Tour::count(),
            "destinationCount" => Destination::count(),
            "newLeadCount" => ConsultationRequest::where(
                "status",
                "new",
            )->count(),
        ]);
    }
}

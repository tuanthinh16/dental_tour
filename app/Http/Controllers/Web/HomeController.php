<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Repositories\DestinationRepository;
use App\Repositories\SettingRepository;
use App\Repositories\TourRepository;
class HomeController extends Controller
{
    public function __construct(
        private DestinationRepository $destinations,
        private TourRepository $tours,
        private SettingRepository $settings,
    ) {}
    public function index()
    {
        return view("home", [
            "destinations" => $this->destinations->active()->take(5),
            "featuredTours" => $this->tours->featured(6),
            "tours" => $this->tours->activePaginated(8),
            "settings" => $this->settings->values(),
        ]);
    }
}

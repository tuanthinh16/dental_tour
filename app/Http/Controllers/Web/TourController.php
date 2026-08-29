<?php
namespace App\Http\Controllers\Web;
use App\Http\Controllers\Controller;
use App\Repositories\TourRepository;
class TourController extends Controller
{
    public function __construct(private TourRepository $tours) {}
    public function index()
    {
        return view("tours.index", [
            "tours" => $this->tours->activePaginated(),
        ]);
    }
    public function show(string $slug)
    {
        return view("tours.show", [
            "tour" => $this->tours->findActiveBySlug($slug),
        ]);
    }
}

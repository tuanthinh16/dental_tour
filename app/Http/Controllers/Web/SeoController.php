<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Tour;
use App\Repositories\SettingRepository;
use App\Support\SeoOptions;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function __construct(private SettingRepository $settings) {}

    public function robots(Request $request): Response
    {
        return response(
            "User-agent: *\nAllow: /\nSitemap: ".$request->root().'/sitemap.xml',
            200,
            ['Content-Type' => 'text/plain; charset=UTF-8'],
        );
    }

    public function sitemap(Request $request): Response
    {
        $tours = Tour::query()
            ->where('is_active', true)
            ->orderBy('updated_at', 'desc')
            ->get(['slug', 'updated_at']);
        $tourPaths = $tours->map(fn (Tour $tour) => '/tours/'.$tour->slug)->all();
        $staticPaths = collect(['/', '/tours'])
            ->merge(SeoOptions::sitemapPaths($this->settings->values()))
            ->reject(fn (string $path) => in_array($path, $tourPaths, true))
            ->unique()
            ->values();

        return response()
            ->view('seo.sitemap', [
                'baseUrl' => $request->root(),
                'staticPaths' => $staticPaths,
                'tours' => $tours,
            ])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }
}

<?php

namespace App\Http\Middleware;

use App\Support\LocaleOptions;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->session()->get('locale', LocaleOptions::DEFAULT);

        app()->setLocale(LocaleOptions::isSupported($locale) ? $locale : LocaleOptions::DEFAULT);

        return $next($request);
    }
}

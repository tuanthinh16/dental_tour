<?php

namespace App\Http\Controllers;

use App\Support\LocaleOptions;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function update(Request $request, string $locale): RedirectResponse
    {
        abort_unless(LocaleOptions::isSupported($locale), 404);

        $request->session()->put('locale', $locale);
        app()->setLocale($locale);

        $redirect = (string) $request->query('redirect', '/');
        if (! str_starts_with($redirect, '/') || str_starts_with($redirect, '//')) {
            $redirect = '/';
        }

        return redirect()->to($redirect);
    }
}

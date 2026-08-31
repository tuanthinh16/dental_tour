<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingRequest;
use App\Http\Requests\Admin\SeoSettingRequest;
use App\Http\Requests\Admin\ThemeSettingRequest;
use App\Models\Setting;
use App\Services\SettingService;
use App\Support\ThemeOptions;
use App\Support\SeoOptions;

class SettingController extends Controller
{
    public function __construct(private SettingService $service) {}

    public function index()
    {
        $settings = $this->service->repository->values();

        return view('admin.settings.index', [
            'theme' => ThemeOptions::normalize($settings),
            'seo' => SeoOptions::normalize($settings),
            'fonts' => ThemeOptions::fontNames(),
        ]);
    }

    public function updateTheme(ThemeSettingRequest $request)
    {
        $this->service->updateTheme($request->validated());

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Đã cập nhật giao diện website.');
    }

    public function updateSeo(SeoSettingRequest $request)
    {
        $this->service->updateSeo($request->validated());

        return redirect()
            ->route('admin.settings.index', ['tab' => 'seo'])
            ->with('success', 'Đã cập nhật cấu hình SEO.');
    }

    public function edit(Setting $setting)
    {
        return view('admin.crud.form', [
            'title' => 'Sửa cài đặt',
            'route' => 'settings',
            'item' => $setting,
            'fields' => [
                'value' => ['label' => 'Giá trị', 'type' => 'textarea'],
            ],
        ]);
    }

    public function update(SettingRequest $r, Setting $setting)
    {
        $this->service->update($setting, $r->validated());

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Đã cập nhật cài đặt.');
    }
}

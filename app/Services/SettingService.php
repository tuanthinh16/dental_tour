<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingService
{
    public function __construct(
        public SettingRepository $repository,
        private MediaService $media,
    ) {}

    public function update(Setting $item, array $data): Setting
    {
        $saved = $this->repository->update($item, $data);
        Log::info('Admin setting updated', [
            'setting_id' => $item->id,
            'key' => $item->key,
        ]);

        return $saved;
    }

    public function updateTheme(array $settings): void
    {
        $this->updateSettings($settings, 'theme');
    }

    public function updateSeo(array $settings): void
    {
        $image = $settings['seo_og_image_upload'] ?? null;
        unset($settings['seo_og_image_upload']);

        if ($image instanceof UploadedFile) {
            $settings['seo_og_image'] = $this->media->uploadSiteImage($image, 'seo');
        }

        $this->updateSettings($settings, 'SEO');
    }

    private function updateSettings(array $settings, string $group): void
    {
        DB::transaction(function () use ($settings): void {
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        });

        Log::info('Admin '.$group.' settings updated', [
            'setting_keys' => array_keys($settings),
        ]);
    }
}

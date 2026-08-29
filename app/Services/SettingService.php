<?php

namespace App\Services;

use App\Models\Setting;
use App\Repositories\SettingRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SettingService
{
    public function __construct(public SettingRepository $repository) {}

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
        DB::transaction(function () use ($settings): void {
            foreach ($settings as $key => $value) {
                Setting::updateOrCreate(['key' => $key], ['value' => $value]);
            }
        });

        Log::info('Admin theme settings updated', [
            'setting_keys' => array_keys($settings),
        ]);
    }
}

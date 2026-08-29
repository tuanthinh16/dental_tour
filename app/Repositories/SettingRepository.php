<?php
namespace App\Repositories;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
class SettingRepository
{
    public function all(): Collection
    {
        return Setting::orderBy("key")->get();
    }
    public function values(): array
    {
        return Setting::pluck("value", "key")->all();
    }
    public function update(Setting $item, array $data): Setting
    {
        $item->update($data);
        return $item;
    }
}

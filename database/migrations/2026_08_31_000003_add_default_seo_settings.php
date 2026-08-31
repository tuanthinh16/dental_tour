<?php

use App\Support\SeoOptions;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 2026-08-31: giá trị SEO mặc định để có thể cấu hình trực tiếp trong CMS.
        foreach (SeoOptions::DEFAULTS as $key => $value) {
            DB::table('settings')->insertOrIgnore([
                'key' => $key,
                'value' => $value,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('settings')->whereIn('key', array_keys(SeoOptions::DEFAULTS))->delete();
    }
};

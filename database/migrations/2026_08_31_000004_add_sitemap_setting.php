<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm 2026-08-31: danh sách URL nội bộ bổ sung có thể chỉnh trong sitemap CMS.
        DB::table('settings')->insertOrIgnore([
            'key' => 'seo_sitemap_urls',
            'value' => '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('settings')->where('key', 'seo_sitemap_urls')->delete();
    }
};

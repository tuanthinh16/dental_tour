<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ($this->fonts() as $key => $fonts) {
            DB::table('settings')
                ->where('key', $key)
                ->update(['value' => $fonts['new']]);
        }
    }

    public function down(): void
    {
        foreach ($this->fonts() as $key => $fonts) {
            DB::table('settings')
                ->where('key', $key)
                ->where('value', $fonts['new'])
                ->update(['value' => $fonts['old']]);
        }
    }

    private function fonts(): array
    {
        return [
            'ui_font_header' => ['old' => 'Satoshi', 'new' => 'Be Vietnam Pro'],
            'ui_font_title' => ['old' => 'Satoshi', 'new' => 'Lora'],
            'ui_font_body' => ['old' => 'Satoshi', 'new' => 'Inter'],
        ];
    }
};

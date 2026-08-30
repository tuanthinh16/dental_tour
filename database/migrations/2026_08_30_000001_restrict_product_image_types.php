<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('product_image', function (Blueprint $table): void {
            $table->enum('image_type', ['list', 'gallery', 'main'])
                ->default('gallery')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('product_image', function (Blueprint $table): void {
            $table->string('image_type', 20)
                ->default('gallery')
                ->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table): void {
            $table->json('translations')->nullable()->after('excluded_items');
        });

        Schema::table('categories', function (Blueprint $table): void {
            $table->json('translations')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table): void {
            $table->dropColumn('translations');
        });

        Schema::table('products', function (Blueprint $table): void {
            $table->dropColumn('translations');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tour_services', function (Blueprint $table): void {
            $table->id();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tour_service_assignments', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tour_service_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['tour_id', 'tour_service_id']);
        });

        if (Schema::hasTable('tour_included_items')) {
            DB::table('tour_included_items')
                ->whereNull('deleted_at')
                ->orderBy('id')
                ->get()
                ->each(function (object $item): void {
                    $serviceId = DB::table('tour_services')
                        ->where('name', $item->content)
                        ->value('id');

                    if (! $serviceId) {
                        $serviceId = DB::table('tour_services')->insertGetId([
                            'name' => $item->content,
                            'sort_order' => $item->sort_order,
                            'is_active' => $item->is_active,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }

                    DB::table('tour_service_assignments')->updateOrInsert(
                        ['tour_id' => $item->tour_id, 'tour_service_id' => $serviceId],
                        ['created_at' => now(), 'updated_at' => now()],
                    );
                });

            Schema::drop('tour_included_items');
        }
    }

    public function down(): void
    {
        Schema::create('tour_included_items', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tour_id')->constrained()->cascadeOnDelete();
            $table->text('content');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('tour_service_assignments')
            ->join('tour_services', 'tour_services.id', '=', 'tour_service_assignments.tour_service_id')
            ->select('tour_service_assignments.tour_id', 'tour_services.name', 'tour_services.sort_order', 'tour_services.is_active')
            ->orderBy('tour_service_assignments.id')
            ->get()
            ->each(function (object $assignment): void {
                DB::table('tour_included_items')->insert([
                    'tour_id' => $assignment->tour_id,
                    'content' => $assignment->name,
                    'sort_order' => $assignment->sort_order,
                    'is_active' => $assignment->is_active,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            });

        Schema::dropIfExists('tour_service_assignments');
        Schema::dropIfExists('tour_services');
    }
};

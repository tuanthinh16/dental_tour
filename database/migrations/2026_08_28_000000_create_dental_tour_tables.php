<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create("admin_users", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("email")->unique();
            $table->string("password");
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("roles", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("code")->unique();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
        Schema::create("permissions", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("code")->unique();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
        Schema::create("admin_user_roles", function (Blueprint $table): void {
            $table->id();
            $table
                ->foreignId("admin_user_id")
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId("role_id")->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(["admin_user_id", "role_id"]);
        });
        Schema::create("role_permissions", function (Blueprint $table): void {
            $table->id();
            $table->foreignId("role_id")->constrained()->cascadeOnDelete();
            $table
                ->foreignId("permission_id")
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
            $table->unique(["role_id", "permission_id"]);
        });
        Schema::create("media", function (Blueprint $table): void {
            $table->id();
            $table->string("file_name");
            $table->string("file_path");
            $table->string("alt_text")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("destinations", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("slug")->unique();
            $table->text("short_description")->nullable();
            $table->longText("description")->nullable();
            $table
                ->foreignId("image_id")
                ->nullable()
                ->constrained("media")
                ->nullOnDelete();
            $table->unsignedInteger("sort_order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("tour_categories", function (Blueprint $table): void {
            $table->id();
            $table->string("name");
            $table->string("slug")->unique();
            $table->unsignedInteger("sort_order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("tours", function (Blueprint $table): void {
            $table->id();
            $table
                ->foreignId("category_id")
                ->nullable()
                ->constrained("tour_categories")
                ->nullOnDelete();
            $table
                ->foreignId("destination_id")
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string("name");
            $table->string("slug")->unique();
            $table->text("short_description");
            $table->longText("description");
            $table->unsignedInteger("duration_days");
            $table->unsignedInteger("duration_nights")->nullable();
            $table->decimal("base_price", 12, 2);
            $table->decimal("original_price", 12, 2)->nullable();
            $table->char("currency", 3)->default("USD");
            $table
                ->foreignId("image_id")
                ->nullable()
                ->constrained("media")
                ->nullOnDelete();
            $table->string("badge")->nullable();
            $table->boolean("is_featured")->default(false);
            $table->unsignedInteger("sort_order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("tour_itineraries", function (Blueprint $table): void {
            $table->id();
            $table->foreignId("tour_id")->constrained()->cascadeOnDelete();
            $table->unsignedInteger("day_number");
            $table->string("title");
            $table->longText("description");
            $table->unsignedInteger("sort_order")->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        foreach (["tour_included_items", "tour_excluded_items"] as $name) {
            Schema::create($name, function (Blueprint $table): void {
                $table->id();
                $table->foreignId("tour_id")->constrained()->cascadeOnDelete();
                $table->text("content");
                $table->unsignedInteger("sort_order")->default(0);
                $table->boolean("is_active")->default(true);
                $table->timestamps();
                $table->softDeletes();
            });
        }
        Schema::create("consultation_requests", function (
            Blueprint $table,
        ): void {
            $table->id();
            $table->string("full_name");
            $table->string("email");
            $table->string("phone", 30);
            $table->string("country")->nullable();
            $table
                ->foreignId("tour_id")
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->date("travel_date")->nullable();
            $table->unsignedInteger("number_of_people")->nullable();
            $table->text("message")->nullable();
            $table->string("status")->default("new")->index();
            $table->string("utm_source")->nullable();
            $table->string("utm_medium")->nullable();
            $table->string("utm_campaign")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("pages", function (Blueprint $table): void {
            $table->id();
            $table->string("title");
            $table->string("slug")->unique();
            $table->longText("content");
            $table->string("seo_title")->nullable();
            $table->text("seo_description")->nullable();
            $table->boolean("is_active")->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::create("settings", function (Blueprint $table): void {
            $table->id();
            $table->string("key")->unique();
            $table->text("value")->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach (
            [
                "settings",
                "pages",
                "consultation_requests",
                "tour_excluded_items",
                "tour_included_items",
                "tour_itineraries",
                "tours",
                "tour_categories",
                "destinations",
                "media",
                "role_permissions",
                "admin_user_roles",
                "permissions",
                "roles",
                "admin_users",
            ]
            as $table
        ) {
            Schema::dropIfExists($table);
        }
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_users', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('admin_user_roles', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('admin_user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['admin_user_id', 'role_id']);
        });

        Schema::create('role_permissions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['role_id', 'permission_id']);
        });

        Schema::create('categories', function (Blueprint $table): void {
            $table->id();
            $table->string('category_code')->unique();
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('products', function (Blueprint $table): void {
            $table->id();
            $table->string('product_code')->unique();
            $table->string('product_type', 30)->index();
            $table->foreignId('destination_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->longText('description')->nullable();
            $table->decimal('daily_price', 15, 2)->default(0);
            $table->decimal('original_daily_price', 15, 2)->nullable();
            $table->char('currency', 3)->default('VND');
            $table->unsignedInteger('duration_days')->nullable();
            $table->unsignedInteger('duration_nights')->nullable();
            $table->string('badge')->nullable();
            $table->text('category_ids')->nullable()->comment('Danh sách category id, phân cách bằng dấu phẩy');
            $table->text('included_product_ids')->nullable()->comment('Danh sách product id đi kèm, phân cách bằng dấu phẩy');
            $table->json('itinerary_data')->nullable();
            $table->json('excluded_items')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['product_type', 'is_active', 'sort_order']);
        });

        Schema::create('product_image', function (Blueprint $table): void {
            $table->id();
            $table->string('product_code');
            $table->enum('image_type', ['list', 'gallery', 'main'])->default('gallery');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('alt_text')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('product_code')->references('product_code')->on('products')->cascadeOnDelete();
            $table->index(['product_code', 'image_type', 'sort_order']);
            $table->unique(['product_code', 'image_type', 'sort_order']);
        });

        Schema::create('consultation_requests', function (Blueprint $table): void {
            $table->id();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone', 30);
            $table->string('country')->nullable();
            $table->foreignId('tour_id')->nullable()->constrained('products')->nullOnDelete();
            $table->date('travel_date')->nullable();
            $table->unsignedInteger('number_of_people')->nullable();
            $table->text('message')->nullable();
            $table->string('status')->default('new')->index();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('pages', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('settings', function (Blueprint $table): void {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        foreach ([
            'settings',
            'pages',
            'consultation_requests',
            'product_image',
            'products',
            'categories',
            'role_permissions',
            'admin_user_roles',
            'permissions',
            'roles',
            'admin_users',
        ] as $table) {
            Schema::dropIfExists($table);
        }
    }
};

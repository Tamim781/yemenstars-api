<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories')) {
            Schema::table('categories', function (Blueprint $table) {
                if (!Schema::hasColumn('categories', 'image_url')) {
                    $table->text('image_url')->nullable();
                }
                if (!Schema::hasColumn('categories', 'sort_order')) {
                    $table->unsignedInteger('sort_order')->default(0);
                }
            });
        }

        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'description')) {
                    $table->text('description')->nullable();
                }
                if (!Schema::hasColumn('products', 'price')) {
                    $table->decimal('price', 10, 2)->default(0);
                }
                if (!Schema::hasColumn('products', 'old_price')) {
                    $table->decimal('old_price', 10, 2)->nullable();
                }
                if (!Schema::hasColumn('products', 'image_url')) {
                    $table->text('image_url')->nullable();
                }
                if (!Schema::hasColumn('products', 'is_available')) {
                    $table->boolean('is_available')->default(true);
                }
                if (!Schema::hasColumn('products', 'is_featured')) {
                    $table->boolean('is_featured')->default(false);
                }
                if (!Schema::hasColumn('products', 'is_daily_special')) {
                    $table->boolean('is_daily_special')->default(false);
                }
            });
        }
    }

    public function down(): void
    {
        // لا نحذف أعمدة من قاعدة البيانات الحالية تلقائياً حفاظاً على البيانات.
    }
};

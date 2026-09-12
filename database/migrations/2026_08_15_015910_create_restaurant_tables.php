<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('categories')) {
            Schema::create('categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('image_url')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->decimal('price', 10, 2);
                $table->decimal('old_price', 10, 2)->nullable();
                $table->text('image_url')->nullable();
                $table->boolean('is_available')->default(true);
                $table->boolean('is_featured')->default(false);
                $table->boolean('is_daily_special')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->string('customer_name');
                $table->string('customer_phone', 50);
                $table->text('address');
                $table->decimal('total_amount', 10, 2);
                $table->string('status')->default('new');
                $table->string('payment_method')->default('cash_on_delivery');
                $table->string('payment_status')->default('pending');
                $table->string('transfer_reference')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('order_items')) {
            Schema::create('order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
                $table->foreignId('product_id')->constrained('products');
                $table->string('product_name');
                $table->decimal('price', 10, 2);
                $table->unsignedInteger('quantity');
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('settings')) {
            Schema::create('settings', function (Blueprint $table) {
                $table->id();
                $table->string('restaurant_name')->default('مطعم نجوم اليمن');
                $table->text('logo_url')->nullable();
                $table->string('phone', 50)->nullable();
                $table->text('address')->nullable();
                $table->string('working_hours')->nullable();
                $table->decimal('delivery_fee', 10, 2)->default(500);
                $table->string('jaib_account', 100)->nullable();
                $table->string('jawali_account', 100)->nullable();
                $table->string('kuraimi_account', 100)->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('products');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('settings');
    }
};

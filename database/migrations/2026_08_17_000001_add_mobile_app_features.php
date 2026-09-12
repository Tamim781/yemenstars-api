<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('phone', 50)->nullable()->after('email');
            });
        }

        if (!Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            });
        }

        if (!Schema::hasTable('addresses')) {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('label')->default('العنوان الرئيسي');
                $table->string('city')->nullable();
                $table->text('address');
                $table->string('phone', 50)->nullable();
                $table->decimal('latitude', 10, 7)->nullable();
                $table->decimal('longitude', 10, 7)->nullable();
                $table->boolean('is_default')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('favorites')) {
            Schema::create('favorites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('product_id')->constrained()->cascadeOnDelete();
                $table->timestamps();
                $table->unique(['user_id', 'product_id']);
            });
        }

        if (!Schema::hasTable('offers')) {
            Schema::create('offers', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->text('image_url')->nullable();
                $table->string('action_type')->default('none');
                $table->unsignedBigInteger('action_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamp('starts_at')->nullable();
                $table->timestamp('ends_at')->nullable();
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('payment_methods')) {
            Schema::create('payment_methods', function (Blueprint $table) {
                $table->id();
                $table->string('code')->unique();
                $table->string('name');
                $table->text('instructions')->nullable();
                $table->string('account_number')->nullable();
                $table->boolean('is_active')->default(true);
                $table->unsignedInteger('sort_order')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('occasions')) {
            Schema::create('occasions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('name');
                $table->date('occasion_date');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'phone')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('phone');
            });
        }

        Schema::dropIfExists('occasions');
        Schema::dropIfExists('payment_methods');
        Schema::dropIfExists('offers');
        Schema::dropIfExists('favorites');
        Schema::dropIfExists('addresses');

        if (Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }
    }
};

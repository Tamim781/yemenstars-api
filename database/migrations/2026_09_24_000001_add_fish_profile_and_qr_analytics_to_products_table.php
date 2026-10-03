<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'benefits')) {
                $table->text('benefits')->nullable()->after('description');
            }
            if (!Schema::hasColumn('products', 'cooking_recommendations')) {
                $table->text('cooking_recommendations')->nullable()->after('benefits');
            }
            if (!Schema::hasColumn('products', 'meat_texture')) {
                $table->string('meat_texture', 255)->nullable()->after('cooking_recommendations');
            }
            if (!Schema::hasColumn('products', 'qr_scans_count')) {
                $table->unsignedInteger('qr_scans_count')->default(0)->after('meat_texture');
            }
            if (!Schema::hasColumn('products', 'qr_orders_count')) {
                $table->unsignedInteger('qr_orders_count')->default(0)->after('qr_scans_count');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $cols = ['benefits', 'cooking_recommendations', 'meat_texture', 'qr_scans_count', 'qr_orders_count'];
            foreach ($cols as $col) {
                if (Schema::hasColumn('products', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};

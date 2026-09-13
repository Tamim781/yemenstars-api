<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            if (!Schema::hasColumn('settings', 'whatsapp_url')) {
                $table->text('whatsapp_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'instagram_url')) {
                $table->text('instagram_url')->nullable();
            }
            if (!Schema::hasColumn('settings', 'facebook_url')) {
                $table->text('facebook_url')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'whatsapp_url',
                'instagram_url',
                'facebook_url',
            ]);
        });
    }
};

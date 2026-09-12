<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->text('whatsapp_url')->nullable();
            $table->text('instagram_url')->nullable();
            $table->text('facebook_url')->nullable();
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

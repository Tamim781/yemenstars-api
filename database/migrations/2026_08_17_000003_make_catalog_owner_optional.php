<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['categories', 'products', 'settings'] as $tableName) {
            if (Schema::hasTable($tableName) && Schema::hasColumn($tableName, 'user_id')) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    $table->unsignedBigInteger('user_id')->nullable()->change();
                });
            }
        }
    }

    public function down(): void
    {
        // لا نعيد user_id إلى required تلقائياً حتى لا نفشل على صفوف عامة موجودة.
    }
};

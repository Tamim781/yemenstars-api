<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role', 30)->default('customer')->after('is_admin');
                $table->index('role');
            });
        }

        if (!Schema::hasColumn('orders', 'claimed_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('claimed_by')->nullable()->after('user_id')->constrained('users')->nullOnDelete();
                $table->timestamp('claimed_at')->nullable()->after('claimed_by');
                $table->index(['status', 'claimed_by']);
            });
        }

        DB::table('users')->where('is_admin', true)->update(['role' => 'admin']);
        DB::table('users')->whereNull('role')->orWhere('role', '')->update(['role' => 'customer']);
    }

    public function down(): void
    {
        if (Schema::hasColumn('orders', 'claimed_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['claimed_by']);
                $table->dropColumn(['claimed_by', 'claimed_at']);
            });
        }

        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex(['role']);
                $table->dropColumn('role');
            });
        }
    }
};


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود الصلاحية إذا لم يكن موجوداً
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }

            // إضافة أعمدة الاشتراك (5 أيام و 5 دولار)
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('password');
            }

            if (!Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('trial_ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'trial_ends_at', 'is_paid']);
        });
    }
};
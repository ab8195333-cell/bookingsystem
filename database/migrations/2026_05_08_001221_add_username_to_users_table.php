<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل التهجير مع فحص ذكي لتجنب أخطاء التكرار.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. تجنب خطأ Duplicate column لعمود username
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('name');
            }

            // 2. إضافة ميزة الـ 5 أيام تجربة (إذا لم تكن موجودة)
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('password');
            }

            // 3. إضافة ميزة دفع الـ 5 دولار (إذا لم تكن موجودة)
            if (!Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('trial_ends_at');
            }
        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'trial_ends_at', 'is_paid']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل التهجير وإضافة ميزات الاشتراك بأمان.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // 1. إضافة ميزة الـ 5 أيام تجربة (إذا لم تكن موجودة)
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('password');
            }

            // 2. إضافة ميزة دفع الـ 5 دولار (إذا لم تكن موجودة)
            if (!Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('trial_ends_at');
            }

            // 3. إضافة الـ username (بشكل اختياري لتجنب خطأ UniqueConstraint)
            if (!Schema::hasColumn('users', 'username')) {
                $table->string('username')->nullable()->after('name');
            }
        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['trial_ends_at', 'is_paid', 'username']);
        });
    }
};
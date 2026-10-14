<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل التهجير لإضافة ميزات الـ 5 أيام والـ 5 دولار.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // إضافة عمود الصلاحية إذا لم يكن موجوداً (لحل مشكلة Duplicate column)
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('user')->after('email');
            }

            // إضافة عمود تاريخ انتهاء الـ 5 أيام (التجربة المجانية)
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('password');
            }

            // إضافة عمود حالة دفع الـ 5 دولار (الاشتراك الشهري)
            if (!Schema::hasColumn('users', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('trial_ends_at');
            }
        });
    }

    /**
     * التراجع عن التعديلات وحذف الأعمدة.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'trial_ends_at', 'is_paid']);
        });
    }
};
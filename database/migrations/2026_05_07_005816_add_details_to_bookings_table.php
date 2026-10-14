<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * تشغيل التهجير وإضافة تفاصيل الحجز بأمان.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // فحص وجود عمود رقم الهاتف قبل إضافته (لحل مشكلة الصورة)
            if (!Schema::hasColumn('bookings', 'phone_number')) {
                $table->string('phone_number')->nullable()->after('id');
            }

            // فحص وجود عمود إيميل العميل (لحل مشكلة الصورة السابقة bab06017)
            if (!Schema::hasColumn('bookings', 'customer_email')) {
                $table->string('customer_email')->nullable()->after('phone_number');
            }

            // يمكنك إضافة أي أعمدة أخرى هنا بنفس الطريقة (بإضافة شرط الفحص)
        });
    }

    /**
     * التراجع عن التعديلات.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'customer_email']);
        });
    }
};
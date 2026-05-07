<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
public function up(): void {
    Schema::table('bookings', function (Blueprint $table) {
        // إضافة الأعمدة في نهاية الجدول مباشرة لتجنب خطأ "Column not found"
        if (!Schema::hasColumn('bookings', 'phone_number')) {
            $table->string('phone_number')->nullable();
        }
        if (!Schema::hasColumn('bookings', 'customer_email')) {
            $table->string('customer_email')->nullable();
        }
        // جعل user_id يقبل قيمة فارغة (حل لمشاكل التوثيق)
        if (Schema::hasColumn('bookings', 'user_id')) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        }
    });
}
    }

    public function down(): void {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['phone_number', 'customer_email']);
        });
    }
};
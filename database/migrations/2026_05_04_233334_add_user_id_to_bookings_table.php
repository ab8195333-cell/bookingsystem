<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // إضافة عمود user_id لربط الحجز بالمستخدم، وجعله يقبل القيم الفارغة مؤقتاً لكي لا يحدث خطأ مع الحجوزات القديمة
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            
            // (اختياري ولكن أفضل) ربط علاقة مفتاح أجنبي مع جدول users
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
};
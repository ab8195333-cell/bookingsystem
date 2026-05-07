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
        // 1. إنشاء جدول المتاجر (Stores)
        if (!Schema::hasTable('stores')) {
            Schema::create('stores', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->timestamps();
            });
        }

        // 2. تحديث جدول المستخدمين (Users)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->onDelete('set null');
            }
        });

        // 3. تحديث جدول الحجوزات (Bookings)
        Schema::table('bookings', function (Blueprint $table) {
            // إضافة عمود المتجر
            if (!Schema::hasColumn('bookings', 'store_id')) {
                $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->onDelete('cascade');
            }
            // التأكد من وجود الأعمدة الأساسية للحجز
            if (!Schema::hasColumn('bookings', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (!Schema::hasColumn('bookings', 'booking_date')) {
                $table->date('booking_date')->nullable();
            }
            // إذا كان مشروعك يستخدم أعمدة أخرى مثل الهاتف أو الإيميل أضفها هنا
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('store_id');
        });

        Schema::dropIfExists('stores');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * تشغيل التهجير لإنشاء جدول الحجوزات.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            
            // ربط الحجز بالمستخدم (صاحب الحساب/المستأجر في نظام SaaS)
            // onDelete('cascade') تضمن حذف حجوزات المستخدم إذا حُذف حسابه
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); 
            
            // بيانات العميل
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone')->nullable(); // إضافة رقم الهاتف مهمة جداً للتواصل
            
            // تفاصيل الحجز
            $table->string('service_name'); // نوع الخدمة المحجوزة
            $table->dateTime('appointment_date'); // تاريخ ووقت الحجز
            
            // حالة الحجز
            $table->enum('status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            
            // حقل إضافي للملاحظات
            $table->text('notes')->nullable();
            
            // طوابع زمنية (created_at & updated_at)
            $table->timestamps();
            
            // Soft Deletes (اختياري: للسماح باستعادة الحجوزات المحذوفة)
            $table->softDeletes(); 

            // تحسين الأداء: إضافة كشاف (Index) للبحث السريع عن الحجوزات حسب التاريخ
            $table->index('appointment_date');
        });
    }

    /**
     * تراجع عن التهجير وحذف الجدول.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
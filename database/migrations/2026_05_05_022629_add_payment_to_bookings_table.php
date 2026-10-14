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
            // قمنا بتغيير 'after' إلى 'id' أو 'customer_name' لضمان عدم حدوث خطأ
            // إذا كان لديك حقل باسم customer_email استخدمه، وإلا فالأفضل وضعه بعد الـ id
            $table->string('payment_status')->default('pending')->after('id');
            $table->decimal('amount', 10, 2)->default(0.00)->after('payment_status');
            $table->string('transaction_id')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'amount', 'transaction_id']);
        });
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // إنشاء جدول المتاجر
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // إضافة عمود المتجر للمستخدمين
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores');
        });

        // إضافة عمود المتجر للحجوزات
        Schema::table('bookings', function (Blueprint $table) {
            $table->foreignId('store_id')->nullable()->after('id')->constrained('stores')->onDelete('cascade');
        });
    }

    public function down() {
        Schema::dropIfExists('stores');
    }
};
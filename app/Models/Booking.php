<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',          // ربط الحجز بالمستخدم الحالي
        'customer_name',    // اسم العميل
        'service_name',     // اسم الخدمة
        'amount',           // المبلغ
        'booking_date',     // تاريخ الحجز
        'appointment_date', // تاريخ الموعد (إجباري في قاعدة البيانات)
        'status',           // حالة الحجز
    ];
}
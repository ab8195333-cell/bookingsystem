<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * الحقول القابلة للتعبئة (Mass Assignable)
     * 
     * تم إضافة 'appointment_date' و 'service_name' لحل مشكلة:
     * "Field doesn't have a default value"
     */
    protected $fillable = [
        'user_id',          // معرف المستخدم صاحب الحجز
        'customer_name',    // اسم العميل
        'service_name',     // اسم الخدمة (المقابل لـ service في الفورم)
        'amount',           // المبلغ
        'booking_date',     // تاريخ الحجز
        'appointment_date', // تاريخ الموعد (الحقل الذي كان يسبب الخطأ الأخير)
    ];

    /**
     * علاقة الحجز بالمستخدم (User)
     * كل حجز ينتمي لمستخدم واحد (صاحب الحساب)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * (إختياري) تنسيق التاريخ عند العرض
     */
    protected $casts = [
        'booking_date' => 'date',
        'appointment_date' => 'date',
    ];
}
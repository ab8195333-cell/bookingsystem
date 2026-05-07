<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Booking extends Model
{
    use HasFactory;

    // اسم الجدول في قاعدة البيانات
    protected $table = 'bookings';

    // الحقول المسموح بتعبئتها (لحماية النظام من Mass Assignment)
    protected $fillable = [
        'customer_name',
        'phone_number',
        'customer_email',
        'status',
        'amount',
        'store_id',
        'user_id',
    ];

    /**
     * علاقة الحجز بالمستخدم (صاحب الحساب/المستأجر)
     * كل حجز ينتمي لمستخدم واحد فقط
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
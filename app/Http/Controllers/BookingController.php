<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Booking::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // حماية تامة: التحقق من المستخدم الحالي وتجنب خطأ null إذا لم يتم إرسال التوكن بشكل صحيح
        $user = $request->user();

        // مثال على التحقق الآمن إذا كنت تستخدم خاصية الاشتراكات
        if ($user && data_get($user, 'is_subscribed') === false) {
            return response()->json([
                'message' => 'عذراً، يجب عليك الاشتراك لتنفيذ هذه العملية.'
            ], 403);
        }

        // التحقق من صحة البيانات القادمة من الفرونت إيند
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'service_name'  => 'required|string|max:255',
            'amount'        => 'required|numeric',
            'booking_date'  => 'required|date',
        ]);

        // إضافة الحجز إلى قاعدة البيانات
        $booking = Booking::create([
            'customer_name' => $validated['customer_name'],
            'service_name'  => $validated['service_name'],
            'amount'        => $validated['amount'],
            'booking_date'  => $validated['booking_date'],
            'user_id'       => $user ? $user->id : null, // ربط الحجز بالمستخدم إن وجد
        ]);

        return response()->json([
            'message' => 'تم حفظ الحجز بنجاح',
            'data'    => $booking
        ], 201);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * استعراض جميع الحجوزات (تستخدمها الواجهة الأمامية لجلب البيانات)
     */
    public function index()
    {
        try {
            $bookings = Booking::all();
            return response()->json($bookings, 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'تعذر جلب الحجوزات',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * حفظ حجز جديد في قاعدة البيانات (Supabase)
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'service' => 'required|string|max:255',
                'amount' => 'required|numeric',
                'booking_date' => 'required|date',
            ]);

            $booking = Booking::create($validated);

            return response()->json([
                'message' => 'تم حفظ الحجز بنجاح ✅',
                'data' => $booking
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'خطأ في التحقق من البيانات',
                'messages' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'حدث خطأ أثناء حفظ الحجز',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
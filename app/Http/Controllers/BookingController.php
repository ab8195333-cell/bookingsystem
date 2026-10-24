<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * استعراض جميع الحجوزات
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
     * حفظ حجز جديد (مرن جداً لقبول أي أسماء حقول قادمة من الفرونت إند)
     */
    public function store(Request $request)
    {
        try {
            // التقاط البيانات بأكثر من احتمال لاسم الحقل لضمان عدم حدوث خطأ
            $customerName = $request->input('customer_name') ?? $request->input('name');
            $serviceName = $request->input('service') ?? $request->input('service_name');
            $amount = $request->input('amount') ?? $request->input('price');
            $bookingDate = $request->input('booking_date') ?? $request->input('appointment_date');

            // التحقق السريع من الحقول الأساسية
            if (!$customerName || !$serviceName || !$amount || !$bookingDate) {
                return response()->json([
                    'success' => false,
                    'error' => 'خطأ في البيانات المرسلة',
                    'message' => 'الرجاء التأكد من إدخال اسم العميل، الخدمة، المبلغ، والتاريخ.'
                ], 422);
            }

            // إنشاء الحجز في قاعدة البيانات (Supabase)
            $booking = Booking::create([
                'customer_name' => $customerName,
                'service_name' => $serviceName,
                'amount' => $amount,
                'booking_date' => $bookingDate,
                'status' => $request->input('status', 'confirmed'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الحجز بنجاح ✅',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'حدث خطأ أثناء حفظ الحجز',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
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
     * حفظ حجز جديد في قاعدة البيانات Supabase
     */
    public function store(Request $request)
    {
        try {
            // التقاط البيانات المرسلة
            $customerName = $request->input('customer_name') ?? $request->input('name');
            $serviceName = $request->input('service_name') ?? $request->input('service');
            $amount = $request->input('amount') ?? $request->input('price') ?? 0;
            $dateInput = $request->input('booking_date') ?? $request->input('appointment_date') ?? now()->toDateString();

            // تجهيز صيغة التاريخ المطلوبة لعمود appointment_date الإجباري في Supabase
            $appointmentDate = date('Y-m-d H:i:s', strtotime($dateInput));

            // إنشاء الحجز وتمرير كل الحقول المطلوبة لقاعدة البيانات
            $booking = Booking::create([
                'customer_name'    => $customerName,
                'service_name'     => $serviceName,
                'amount'           => $amount,
                'booking_date'     => $dateInput,
                'appointment_date' => $appointmentDate, // تزويد العمود الإجباري بالحقل المطلوب
                'status'           => $request->input('status', 'confirmed'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الحجز بنجاح ✅',
                'data'    => $booking
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => 'حدث خطأ أثناء حفظ الحجز',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
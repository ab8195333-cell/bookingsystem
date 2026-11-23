<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller{

    // عرض حجوزات المستخدم المصادق عليه فقط
    public function index(Request $request)
    {
        try {
            $user = $request->user();
            $bookings = Booking::where('user_id', $user->id)->get();
            
            return response()->json([
                'success' => true,
                'data' => [
                    'bookings' => $bookings,
                    'bookingsCount' => $bookings->count(),
                    'totalAmount' => $bookings->sum('amount')
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // حفظ حجز جديد مرتبط بالمستخدم الحالي مع التحقق من البيانات
   public function store(Request $request)
    {
        try {
            // التحقق من صحة البيانات الواردة مع جعل الحقول الأساسية إجبارية
            $validated = $request->validate([
                'customer_name'    => 'nullable|string|max:255',
                'name'             => 'nullable|string|max:255',
                'service_name'     => 'required|string|max:255', // أصبحت إجبارية للاختبار
                'service'          => 'nullable|string|max:255',
                'amount'           => 'nullable|numeric|min:0',
                'price'            => 'nullable|numeric|min:0',
                'booking_date'     => 'required|date',           // أصبحت إجبارية للاختبار
                'appointment_date' => 'nullable|date',
                'status'           => 'nullable|string|in:pending,confirmed,cancelled',
            ]);

            $user = $request->user();

            $booking = Booking::create([
                'user_id'          => $user->id, // ربط آمن بالمستخدم
                'customer_name'    => $validated['customer_name'] ?? $validated['name'] ?? $user->name,
                'service_name'     => $validated['service_name'] ?? $validated['service'] ?? 'General Service',
                'amount'           => $validated['amount'] ?? $validated['price'] ?? 0,
                'booking_date'     => $validated['booking_date'],
                'appointment_date' => $validated['appointment_date'] ?? now(),
                'status'           => $validated['status'] ?? 'confirmed',
            ]);

            return response()->json([
                'success' => true, 
                'message' => 'تم إنشاء الحجز بنجاح',
                'data' => $booking
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // عرض حجز معين والتأكد أنه يخص المستخدم نفسه
 // عرض تفاصيل حجز معين مع التحقق من الملكية
   // عرض تفاصيل حجز معين مع التحقق من الملكية
    public function show(Request $request, Booking $booking)
    {
        if ((int) $booking->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مخول بالوصول إلى هذا الحجز'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $booking
        ]);
    }

    // تحديث الحجز مع التحقق من الملكية
    public function update(Request $request, Booking $booking)
    {
        if ((int) $booking->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مخول بتعديل هذا الحجز'
            ], 403);
        }

        $validated = $request->validate([
            'customer_name'    => 'nullable|string|max:255',
            'service_name'     => 'sometimes|required|string|max:255',
            'amount'           => 'nullable|numeric|min:0',
            'booking_date'     => 'sometimes|required|date',
            'appointment_date' => 'nullable|date',
            'status'           => 'nullable|string|in:pending,confirmed,cancelled',
        ]);

        $booking->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الحجز بنجاح',
            'data' => $booking
        ]);
    }

    // حذف الحجز مع التحقق من الملكية
    public function destroy(Request $request, Booking $booking)
    {
        if ((int) $booking->user_id !== (int) $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مخول بحذف هذا الحجز'
            ], 403);
        }

        $booking->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الحجز بنجاح'
        ]);
    }
}
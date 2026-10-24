<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
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
            // التحقق من صحة البيانات الواردة
            $validated = $request->validate([
                'customer_name'    => 'nullable|string|max:255',
                'name'             => 'nullable|string|max:255',
                'service_name'     => 'nullable|string|max:255',
                'service'          => 'nullable|string|max:255',
                'amount'           => 'nullable|numeric|min:0',
                'price'            => 'nullable|numeric|min:0',
                'booking_date'     => 'nullable|date',
                'appointment_date' => 'nullable|date',
                'status'           => 'nullable|string|in:pending,confirmed,cancelled',
            ]);

            $user = $request->user();

            $booking = Booking::create([
                'user_id'          => $user->id, // ربط آمن بالمستخدم
                'customer_name'    => $validated['customer_name'] ?? $validated['name'] ?? $user->name,
                'service_name'     => $validated['service_name'] ?? $validated['service'] ?? 'General Service',
                'amount'           => $validated['amount'] ?? $validated['price'] ?? 0,
                'booking_date'     => $validated['booking_date'] ?? now()->toDateString(),
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
    public function show(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', $request->user()->id)
                          ->firstOrFail();

        return response()->json([
            'success' => true, 
            'data' => $booking
        ], 200);
    }

    // تحديث حجز يخص المستخدم فقط بشكل آمن
    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::where('id', $id)
                              ->where('user_id', $request->user()->id)
                              ->firstOrFail();

            // التحقق من البيانات المسموح بتحديثها فقط (منع التلاعب بـ user_id)
            $validated = $request->validate([
                'customer_name'    => 'sometimes|string|max:255',
                'service_name'     => 'sometimes|string|max:255',
                'amount'           => 'sometimes|numeric|min:0',
                'booking_date'     => 'sometimes|date',
                'appointment_date' => 'sometimes|date',
                'status'           => 'sometimes|string|in:pending,confirmed,cancelled',
            ]);

            $booking->update($validated);

            return response()->json([
                'success' => true, 
                'message' => 'تم التحديث بنجاح', 
                'data' => $booking
            ], 200);

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

    // حذف حجز يخص المستخدم فقط
    public function destroy(Request $request, $id)
    {
        $booking = Booking::where('id', $id)
                          ->where('user_id', $request->user()->id)
                          ->firstOrFail();
                          
        $booking->delete();

        return response()->json([
            'success' => true, 
            'message' => 'تم الحذف بنجاح'
        ], 200);
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    // عرض حجوزات المستخدم المصادق عليه فقط
    public function index(Request $request)
    {
        try {
            $user = $request->user(); // المستخدم الحالي عبر Sanctum
            $bookings = Booking::where('user_id', $user->id)->get();
            
            return response()->json([
                'status' => 'success',
                'data' => [
                    'bookings' => $bookings,
                    'bookingsCount' => $bookings->count(),
                    'totalAmount' => $bookings->sum('amount')
                ]
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // حفظ حجز جديد مرتبط بالمستخدم الحالي
    public function store(Request $request)
    {
        try {
            $user = $request->user();

            $booking = Booking::create([
                'user_id'          => $user->id, // ربط الحجز بالمستخدم
                'customer_name'    => $request->input('customer_name') ?? $request->input('name'),
                'service_name'     => $request->input('service_name') ?? $request->input('service'),
                'amount'           => $request->input('amount') ?? $request->input('price') ?? 0,
                'booking_date'     => $request->input('booking_date') ?? now()->toDateString(),
                'appointment_date' => $request->input('appointment_date') ?? now(),
                'status'           => $request->input('status', 'confirmed'),
            ]);

            return response()->json(['success' => true, 'data' => $booking], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // عرض حجز معين والتأكد أنه يخص المستخدم نفسه
    public function show(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        return response()->json(['status' => 'success', 'data' => $booking], 200);
    }

    // تحديث حجز يخص المستخدم فقط
    public function update(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        
        $booking->update($request->all());

        return response()->json(['success' => true, 'message' => 'تم التحديث بنجاح', 'data' => $booking], 200);
    }

    // حذف حجز يخص المستخدم فقط
    public function destroy(Request $request, $id)
    {
        $booking = Booking::where('id', $id)->where('user_id', $request->user()->id)->firstOrFail();
        $booking->delete();

        return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح'], 200);
    }
}
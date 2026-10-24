<?php

namespace App\Controllers; // أو namespace App\Http\Controllers حسب إعداداتك

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::all(), 200);
    }

    public function store(Request $request)
    {
        try {
            $customerName = $request->input('customer_name') ?? $request->input('name');
            $serviceName = $request->input('service_name') ?? $request->input('service');
            $amount = $request->input('amount') ?? $request->input('price') ?? 0;
            $dateInput = $request->input('booking_date') ?? $request->input('appointment_date') ?? now()->toDateString();
            $appointmentDate = date('Y-m-d H:i:s', strtotime($dateInput));

            $booking = Booking::create([
                'customer_name'    => $customerName,
                'service_name'     => $serviceName,
                'amount'           => $amount,
                'booking_date'     => $dateInput,
                'appointment_date' => $appointmentDate,
                'status'           => $request->input('status', 'confirmed'),
            ]);

            return response()->json(['success' => true, 'data' => $booking], 201);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // دالة تحديث الحجز
    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::findOrFail($id);
            
            $booking->update([
                'customer_name'    => $request->input('customer_name', $booking->customer_name),
                'service_name'     => $request->input('service_name', $booking->service_name),
                'amount'           => $request->input('amount', $booking->amount),
                'booking_date'     => $request->input('booking_date', $booking->booking_date),
                'status'           => $request->input('status', $booking->status),
            ]);

            return response()->json(['success' => true, 'message' => 'تم التحديث بنجاح', 'data' => $booking], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    // دالة حذف الحجز
    public function destroy($id)
    {
        try {
            $booking = Booking::findOrFail($id);
            $booking->delete();

            return response()->json(['success' => true, 'message' => 'تم الحذف بنجاح'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
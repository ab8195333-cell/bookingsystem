<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    /**
     * عرض قائمة الحجوزات مع الإحصائيات وفحص الاشتراك
     */
    public function index()
    {
        $user = Auth::user();

        // فحص الاشتراك: إذا انتهت الفترة التجريبية ولم يشترك
        if (!$user->is_subscribed && now()->gt($user->created_at->addDays(5))) {
            return response()->json([
                'status'  => 'subscription_required',
                'message' => 'انتهت الفترة التجريبية، يرجى تفعيل الحساب.'
            ], 403);
        }

        $bookings = Booking::where('user_id', $user->id)->get();
        $bookingsCount = $bookings->count(); 
        $totalAmount = $bookings->sum('amount');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'bookings'      => $bookings,
                'bookingsCount' => $bookingsCount,
                'totalAmount'   => $totalAmount,
            ]
        ], 200);
    }

    /**
     * إضافة حجز جديد
     */
public function store(Request $request)
    {
        \Log::info('Booking request:', $request->all());

        try {
            $booking = new \App\Models\Booking();
            
            // مطابقة الحقول تماماً لما يأتي من Next.js (مع قيم افتراضية آمنة)
            $booking->customer_name = $request->input('customer_name') ?? $request->input('name', 'عميل');
            $booking->service_name  = $request->input('service_name') ?? $request->input('service', 'خدمة عامة');
            $booking->amount        = $request->input('amount') ?? $request->input('price', 0);
            $booking->booking_date  = $request->input('booking_date') ?? $request->input('date', now());
            
            // ربط الحجز بالمستخدم الحالي أو استخدام المستخدم الافتراضي (1)
            $booking->user_id = Auth::id() ?? 1;
            
            $booking->save();

            return response()->json([
                'status' => 'success',
                'message' => 'تم الحجز بنجاح',
                'data' => $booking
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * جلب بيانات حجز معين للتعديل (بدلاً من عرض صفحة التعديل)
     */
    public function show($id)
    {
        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $booking
        ], 200);
    }

    /**
     * تعديل حجز قائم
     */
    public function update(Request $request, $id)
    {
        // التحقق من كلمة المرور للتأكيد
        if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => '❌ كلمة المرور غير صحيحة، فشل التعديل!'
            ], 401);
        }

        $request->validate([
            'customer_name' => 'required|string',
            'service_name'  => 'required|string',
            'amount'        => 'required|numeric',
            'booking_date'  => 'required|date',
        ]);

        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        $booking->update([
            'customer_name' => $request->customer_name,
            'service_name'  => $request->service_name,
            'amount'        => $request->amount,
            'booking_date'  => $request->booking_date,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => '✅ تم تعديل الحجز بنجاح.',
            'data'    => $booking
        ], 200);
    }

    /**
     * حذف حجز
     */
    public function destroy(Request $request, $id)
    {
        // التحقق من كلمة المرور
        if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
            return response()->json([
                'status'  => 'error',
                'message' => '❌ كلمة المرور غير صحيحة! لم يتم الحذف.'
            ], 401);
        }

        $booking = Booking::where('user_id', Auth::id())->findOrFail($id);
        $booking->delete();

        return response()->json([
            'status'  => 'success',
            'message' => '✅ تم حذف الحجز بنجاح.'
        ], 200);
    }

    /**
     * جلب بيانات التقرير الكامل
     */
    public function downloadFullReport()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();

        return response()->json([
            'status' => 'success',
            'data'   => [
                'bookings'      => $bookings,
                'bookingsCount' => $bookings->count(),
                'totalAmount'   => $bookings->sum('amount'),
            ]
        ], 200);
    }

    /**
     * إنشاء رابط الدفع Stripe وإرجاعه للـ Front-End
     */
    public function stripeCheckout()
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'اشتراك الباقة الشاملة - نظام الحجوزات',
                        ],
                        'unit_amount' => 1500, // 15.00$
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                // روابط العودة إلى تطبيق Next.js
                'success_url' => config('app.frontend_url') . '/bookings?success=true',
                'cancel_url' => config('app.frontend_url') . '/bookings?canceled=true',
            ]);

            return response()->json([
                'status'      => 'success',
                'checkout_url' => $session->url
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'خطأ في اتصال بوابة الدفع: ' . $e->getMessage()
            ], 500);
        }
    }
}
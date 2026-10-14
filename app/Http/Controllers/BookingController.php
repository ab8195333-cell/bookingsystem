<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // فحص الاشتراك: إذا لم يشترك ومرت 5 أيام على تسجيله، حوله لصفحة الدفع
        if (!$user->is_subscribed && now()->gt($user->created_at->addDays(5))) {
            return redirect()->route('subscription.index')->with('error', 'انتهت الفترة التجريبية، يرجى تفعيل الحساب.');
        }

        $bookings = Booking::where('user_id', $user->id)->get();
        $bookingsCount = $bookings->count(); 
        $totalAmount = $bookings->sum('amount');

        return view('bookings.index', compact('bookings', 'bookingsCount', 'totalAmount'));
    }

    public function subscribe()
    {
        return view('subscriptions.index');
    }

    public function startTrial()
    {
        // هنا يمكنك إضافة منطق تحديث قاعدة البيانات لمرة واحدة فقط
        return redirect()->route('bookings.index')->with('success', 'تم تفعيل الفترة التجريبية بنجاح!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'service' => 'required',
            'amount' => 'required|numeric',
            'booking_date' => 'required|date',
        ]);

        Booking::create([
            'user_id' => Auth::id(),
            'customer_name' => $request->customer_name,
            'service_name' => $request->service,
            'amount' => $request->amount,
            'booking_date' => $request->booking_date,
            'appointment_date' => $request->booking_date,
        ]);

        return redirect()->back()->with('success', 'تمت الإضافة بنجاح');
    }

    public function downloadFullReport()
    {
        $bookings = Booking::where('user_id', Auth::id())->get();
        $pdf = Pdf::loadView('bookings.pdf', ['bookings' => $bookings, 'bookingsCount' => $bookings->count(), 'totalAmount' => $bookings->sum('amount')]);
        return $pdf->download('report.pdf');
    }

    public function destroy($id)
    {
        Booking::where('id', $id)->where('user_id', Auth::id())->delete();
        return redirect()->back();
    }

public function checkout()
    {
        return view('subscriptions.payment-options');
    }

    public function stripeCheckout()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        try {
            $session = \Stripe\Checkout\Session::create([
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'اشتراك الباقة الشاملة - نظام الحجوزات',
                        ],
                        'unit_amount' => 1500, // 15.00 دولار
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('bookings.index') . '?success=true',
                'cancel_url' => route('subscription.checkout'),
            ]);

            return redirect($session->url);
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'خطأ في اتصال بوابة الدفع: ' . $e->getMessage()]);
        }
    }

}
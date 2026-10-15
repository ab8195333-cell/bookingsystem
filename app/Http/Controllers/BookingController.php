<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class BookingController extends Controller
{
    // دالة عرض الحجوزات الأساسية
    public function index()
    {
        $bookings = Booking::latest()->paginate(10);
        return view('bookings.index', compact('bookings'));
    }

    // دالة تخزين حجز جديد
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'service_name'  => 'required|string|max:255',
            'amount'        => 'required|numeric',
            'booking_date'  => 'required|date',
        ]);

        Booking::create([
            'customer_name' => $request->customer_name,
            'service_name'  => $request->service_name,
            'amount'        => $request->amount,
            'booking_date'  => $request->booking_date,
            'user_id'       => Auth::id(),
        ]);

        return redirect()->route('bookings.index')->with('success', '✅ تم إضافة الحجز بنجاح.');
    }

    // 1. حذف حجز
    public function destroy(Request $request, $id)
    {
        // التحقق هل أدخل كلمة المرور وهل هي صحيحة بحسب حساب المستخدم المسجل حالياً
        if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
            return redirect()->back()->with('error', '❌ كلمة المرور غير صحيحة! لم يتم الحذف.');
        }

        // تنفيذ الحذف عند مطابقة كلمة المرور
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return redirect()->back()->with('success', '✅ تم حذف الحجز بنجاح.');
    }

    // 2. عرض صفحة التعديل مع فحص كلمة السر
    public function edit(Request $request, $id)
    {
        // التحقق من كلمة المرور المرسلة عبر الطلب
        if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
            return redirect()->route('bookings.index')->with('error', '❌ كلمة المرور غير صحيحة، فشل فتح صفحة التعديل!');
        }

        $booking = Booking::findOrFail($id);
        return view('bookings.edit', compact('booking'));
    }

    // 3. معالجة وتحديث البيانات مع فحص كلمة المرور
    public function update(Request $request, $id)
    {
        // التحقق من كلمة المرور
        if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
            return redirect()->back()->with('error', '❌ كلمة المرور غير صحيحة، فشل التعديل!');
        }

        // التحديث
        $booking = Booking::findOrFail($id);
        $booking->update([
            'customer_name' => $request->customer_name,
            'service_name'  => $request->service_name,
            'amount'        => $request->amount,
            'booking_date'  => $request->booking_date,
        ]);

        return redirect()->route('bookings.index')->with('success', '✅ تم تعديل الحجز بنجاح.');
    }

    // دالة تحميل التقرير الكامل PDF
    public function downloadFullReport()
    {
        $bookings = Booking::all();
        // منطق طباعة التقرير أو تصديره
        return view('bookings.report-print', compact('bookings'));
    }

    // مسارات إضافية تجريبية
    public function subscribe()
    {
        return view('subscription.index');
    }

    public function startTrial(Request $request)
    {
        return redirect()->route('bookings.index')->with('success', '✅ تم تفعيل التجربة بنجاح.');
    }

    public function checkout()
    {
        return view('subscription.checkout');
    }

    public function stripeCheckout(Request $request)
    {
        return redirect()->route('bookings.index')->with('success', '✅ تم الدفع بنجاح.');
    }
}
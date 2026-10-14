<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Hash;
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
    $user = Auth::user();
    
    // جلب حجوزات المستخدم
    $bookings = Booking::where('user_id', $user->id)->get();
    
    $bookingsCount = $bookings->count();
    $totalAmount = $bookings->sum('amount');

    // توليد ملف الـ PDF حتى لو كان عدد الحجوزات صفراً لكي لا يحدث خطأ
    $pdf = Pdf::loadView('bookings.pdf', [
        'bookings' => $bookings, 
        'bookingsCount' => $bookingsCount, 
        'totalAmount' => $totalAmount
    ]);

    return $pdf->download('bookings-report.pdf');
}

   

public function checkout()
    {
        return view('subscriptions.payment-options');
    }

   public function stripeCheckout()
{
    // استخدام config بدلاً من env لتجنب مشاكل الـ Caching
    \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

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






public function destroy(Request $request, $id)
{
    // 1. التحقق من صلاحية الأدمن
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'غير مصرح لك بالوصول.');
    }

    // 2. التحقق هل أدخل كلمة المرور وهل هي صحيحة بحسب حساب الأدمن المسجل
    if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
        return redirect()->back()->with('error', '❌ كلمة المرور غير صحيحة! لم يتم الحذف.');
    }

    // 3. تنفيذ الحذف عند مطابقة كلمة المرور
    $booking = Booking::findOrFail($id);
    $booking->delete();

    return redirect()->back()->with('success', '✅ تم حذف الحجز بنجاح.');
}




// 1. عرض صفحة التعديل (أو فحص كلمة السر أولاً)
public function edit(Request $request, $id)
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'غير مصرح لك.');
    }

    // التحقق من كلمة المرور المرسلة عبر الرابط من نافذة الـ prompt
    if (!$request->filled('admin_password') || !Hash::check($request->admin_password, Auth::user()->password)) {
        return redirect()->route('bookings.index')->with('error', '❌ كلمة المرور غير صحيحة، فشل فتح صفحة التعديل!');
    }

    $booking = Booking::findOrFail($id);
    return view('bookings.edit', compact('booking'));
}

// 2. معالجة وتحديث البيانات مع فحص كلمة المرور
public function update(Request $request, $id)
{
    if (!auth()->check() || auth()->user()->role !== 'admin') {
        abort(403, 'غير مصرح لك.');
    }

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




}
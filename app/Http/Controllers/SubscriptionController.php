<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * عرض صفحة الانتظار أو تحويل المستخدم إذا تم التفعيل
     */
    public function waitingPage()
    {
        $user = Auth::user();

        // إذا الاشتراك مفعل يحوله للوحة التحكم
        if ($user && $user->subscription_status == 'active') {
            return redirect('/dashboard');
        }

        // إذا مازال قيد المراجعة
        return view('subscription.waiting');
    }

    /**
     * معالجة رفع السند
     */
    public function uploadReceipt(Request $request)
    {
        // التحقق من البيانات
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . Auth::id(),
            'receipt'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'username.unique' => 'اسم المستخدم أو رابط المحل محجوز مسبقاً.',
            'receipt.required' => 'يرجى رفع صورة السند البنكي.',
        ]);

        $user = Auth::user();

        // رفع الصورة
        if ($request->hasFile('receipt')) {

            $path = $request->file('receipt')->store('receipts', 'public');

            // تحديث بيانات المستخدم
            $user->update([
                'username' => $request->username,
                'payment_receipt' => $path,
                'subscription_status' => 'pending',
            ]);

            return redirect()->back()->with('success', 'تم رفع البيانات بنجاح! بانتظار تفعيل الإدارة.');
        }

        return redirect()->back()->withErrors([
            'receipt' => 'حدث خطأ أثناء رفع الصورة.'
        ]);
    }
}
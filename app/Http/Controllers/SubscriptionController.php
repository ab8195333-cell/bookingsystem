<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SubscriptionController extends Controller
{
    /**
     * التحقق من حالة اشتراك المستخدم (تُستخدم لفلترة التوجيه في الفرونت إند)
     */
    public function checkStatus()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'غير مصرح'], 401);
        }

        return response()->json([
            'subscription_status' => $user->subscription_status,
        ], 200);
    }

    /**
     * معالجة تفعيل التجربة المجانية (Trial)
     */
    public function trial(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'غير مصرح'], 401);
        }

        // تحديث حالة الاشتراك إلى نشط أو تجريبي حسب نظامك
        $user->update([
            'subscription_status' => 'active', // أو 'trial' إذا أردت تمييزها
        ]);

        return response()->json([
            'message' => 'تم تفعيل التجربة المجانية بنجاح!',
            'status' => 'success'
        ], 200);
    }

    /**
     * معالجة رفع السند البنكي
     */
    public function uploadReceipt(Request $request)
    {
        // التحقق من البيانات مع إرجاع أخطاء JSON إذا فشل التحقق
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,' . Auth::id(),
            'receipt'  => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'username.unique' => 'اسم المستخدم أو رابط المحل محجوز مسبقاً.',
            'receipt.required' => 'يرجى رفع صورة السند البنكي.',
        ]);

        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'غير مصرح'], 401);
        }

        // رفع الصورة
        if ($request->hasFile('receipt')) {
            $path = $request->file('receipt')->store('receipts', 'public');

            // تحديث بيانات المستخدم
            $user->update([
                'username' => $request->username,
                'payment_receipt' => $path,
                'subscription_status' => 'pending',
            ]);

            return response()->json([
                'message' => 'تم رفع السند بنجاح! بانتظار تفعيل الإدارة.',
                'status' => 'success'
            ], 200);
        }

        return response()->json([
            'message' => 'حدث خطأ أثناء رفع الصورة.'
        ], 422);
    }
}
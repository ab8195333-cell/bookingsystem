<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSubscription
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // السماح بالمرور إذا دفع الـ 5 دولار
        if ($user->is_paid) {
            return $next($request);
        }

        // الحالة أ: مستخدم جديد لم يضغط على أي زر (trial_ends_at فارغ)
        if (is_null($user->trial_ends_at)) {
            if (!$request->is('choose-plan') && !$request->is('activate-trial') && !$request->is('payment-required')) {
                return redirect()->route('subscription.choice');
            }
        }

        // الحالة ب: اختار التجربة وانتهت الـ 5 أيام ولم يدفع
        if (!is_null($user->trial_ends_at) && now()->gt($user->trial_ends_at)) {
            if (!$request->is('payment-required')) {
                return redirect()->route('subscription.pay');
            }
        }

        return $next($request);
    }
}
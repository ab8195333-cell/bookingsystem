<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    | هذا المتحكم مسؤول عن التحقق من بيانات المستخدم وتوجيهه بعد الدخول.
    */

    use AuthenticatesUsers;

    /**
     * المسار الذي يتم توجيه المستخدم إليه بعد تسجيل الدخول.
     * تم تعديله من /home إلى /bookings لحل خطأ 404 الظاهر في الصورة c61485b2.
     *
     * @var string
     */
    protected $redirectTo = '/bookings';

    /**
     * إنشاء نسخة جديدة من المتحكم.
     */
    public function __construct()
    {
        // يمنع المستخدم المسجل دخولاً بالفعل من رؤية صفحة الدخول مرة أخرى
        $this->middleware('guest')->except('logout');
        
        // يسمح للمستخدم بتسجيل الخروج في أي وقت
        $this->middleware('auth')->only('logout');
    }
}
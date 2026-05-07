@extends('layouts.app')

@section('content')
<style>
    /* حاوية تجعل المحتوى في المنتصف تماماً */
    .login-wrapper {
        display: flex;
        justify-content: center; /* التوسط العرضي */
        align-items: center;     /* التوسط الطولي */
        min-height: 80vh;        /* يأخذ معظم ارتفاع الشاشة */
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card { 
        background: white; 
        padding: 40px; 
        border-radius: 20px; 
        width: 100%; 
        max-width: 420px; 
        box-shadow: 0 15px 35px rgba(0,0,0,0.1); 
        border: 1px solid #f0f0f0;
    }

    .login-title {
        color: #333;
        font-weight: 700;
        margin-bottom: 30px;
        font-size: 24px;
    }

    .login-card input { 
        width: 100%; 
        padding: 14px; 
        margin: 12px 0; 
        border: 1px solid #e0e0e0; 
        border-radius: 10px; 
        font-size: 16px; 
        box-sizing: border-box;
        text-align: right;
        background-color: #fafafa;
        transition: 0.3s;
    }

    .login-card input:focus {
        border-color: #007bff;
        background-color: #fff;
        outline: none;
        box-shadow: 0 0 0 4px rgba(0,123,255,0.1);
    }

    .login-card button { 
        background: #007bff; 
        color: white; 
        border: none; 
        padding: 14px; 
        cursor: pointer; 
        border-radius: 10px; 
        width: 100%; 
        font-weight: bold; 
        font-size: 18px;
        transition: 0.3s;
        margin-top: 20px;
    }

    .login-card button:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }

    .remember-me {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 10px;
        color: #666;
        font-size: 14px;
    }
</style>

<div class="login-wrapper" dir="rtl">
    <div class="login-card text-center">
        <h2 class="login-title">تسجيل الدخول</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            {{-- البريد الإلكتروني --}}
            <input type="email" name="email" placeholder="البريد الإلكتروني" value="{{ old('email') }}" required autofocus>
            @error('email')
                <span class="text-danger small d-block text-end" style="margin-top:-8px">{{ $message }}</span>
            @enderror
            
            {{-- كلمة المرور --}}
            <input type="password" name="password" placeholder="كلمة المرور" required>
            @error('password')
                <span class="text-danger small d-block text-end" style="margin-top:-8px">{{ $message }}</span>
            @enderror

            <div class="remember-me">
                <label for="remember">تذكرني</label>
                <input type="checkbox" name="remember" id="remember" style="width: auto; margin: 0;">
            </div>

            <button type="submit">دخول</button>
        </form>

        @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}" style="display:block; margin-top:20px; color:#007bff; text-decoration:none; font-size: 14px;">
                نسيت كلمة المرور؟
            </a>
        @endif
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-body p-5">
                    <!-- العنوان -->
                    <div class="text-center mb-4">
                        <h3 class="fw-bold text-primary">إنشاء حساب جديد</h3>
                        <p class="text-muted small">سجل بياناتك للبدء في استخدام النظام</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- حقل الاسم الكامل -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small">الاسم الكامل</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-user text-muted"></i></span>
                                <input type="text" name="name" class="form-control bg-light border-start-0 @error('name') is-invalid @enderror" 
                                       value="{{ old('name') }}" placeholder="أدخل اسمك الكامل" required autofocus>
                            </div>
                            @error('name')
                                <span class="text-danger small"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- حقل اسم المستخدم (Username) - الحقل الجديد -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small">اسم المستخدم (Username)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-at text-muted"></i></span>
                                <input type="text" name="username" class="form-control bg-light border-start-0 @error('username') is-invalid @enderror" 
                                       value="{{ old('username') }}" placeholder="مثال: ahmed_2024" required>
                            </div>
                            @error('username')
                                <span class="text-danger small"><strong>{{ $message }}</strong></span>
                            @enderror
                            <div class="form-text x-small text-muted">هذا الاسم سيظهر في ملفك الشخصي.</div>
                        </div>

                        <!-- حقل البريد الإلكتروني -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}" placeholder="example@mail.com" required>
                            </div>
                            @error('email')
                                <span class="text-danger small"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- حقل كلمة المرور -->
                        <div class="mb-3">
                            <label class="form-label fw-bold small">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                       placeholder="********" required>
                            </div>
                            @error('password')
                                <span class="text-danger small"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- حقل تأكيد كلمة المرور -->
                        <div class="mb-4">
                            <label class="form-label fw-bold small">تأكيد كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fas fa-check-double text-muted"></i></span>
                                <input type="password" name="password_confirmation" class="form-control bg-light border-start-0" 
                                       placeholder="********" required>
                            </div>
                        </div>

                        <!-- زر إنشاء الحساب -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-2">
                                <i class="fas fa-user-plus me-2"></i> إنشاء الحساب
                            </button>
                        </div>

                        <!-- رابط العودة لتسجيل الدخول -->
                        <div class="text-center mt-4">
                            <p class="text-muted small mb-0">لديك حساب بالفعل؟ 
                                <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none border-bottom border-primary border-2 pb-1">سجل دخولك الآن</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* تحسينات التصميم */
    body { background-color: #f0f2f5; }
    .card { border: none !important; transition: transform 0.3s ease; }
    .input-group-text { border: 1px solid #e0e0e0; border-radius: 10px 0 0 10px !important; }
    .form-control { border: 1px solid #e0e0e0; border-radius: 0 10px 10px 0 !important; padding: 12px; }
    .form-control:focus { box-shadow: none; border-color: #0d6efd; background-color: #fff !important; }
    .btn-primary { background: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); border: none; }
    .btn-primary:hover { background: linear-gradient(135deg, #0043a8 0%, #0d6efd 100%); transform: translateY(-1px); }
    .x-small { font-size: 0.75rem; }
</style>
@endsection
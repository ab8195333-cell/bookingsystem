@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-primary py-4 text-center border-0">
                    <h4 class="text-white fw-bold mb-0">مرحباً بك في BOOKINGSAAS</h4>
                </div>
                <div class="card-body p-5 bg-white">
                    <h5 class="text-center fw-bold mb-4 text-secondary">تسجيل الدخول</h5>
                    
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold">البريد الإلكتروني</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-envelope text-muted"></i></span>
                                <input type="email" name="email" class="form-control bg-light border-0 @error('email') is-invalid @enderror" placeholder="name@example.com" value="{{ old('email') }}" required autofocus>
                            </div>
                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">كلمة المرور</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0"><i class="fas fa-lock text-muted"></i></span>
                                <input type="password" name="password" class="form-control bg-light border-0 @error('password') is-invalid @enderror" placeholder="••••••••" required>
                            </div>
                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                        </div>

                        <div class="d-grid mb-4">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold rounded-3 shadow-sm">
                                دخول <i class="fas fa-sign-in-alt ms-2"></i>
                            </button>
                        </div>

                        <div class="text-center pt-3 border-top">
                            <p class="text-muted mb-1">ليس لديك حساب حتى الآن؟</p>
                            <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 fw-bold rounded-3 mt-2">
                                <i class="fas fa-user-plus me-1"></i> إنشاء حساب جديد (Register)
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
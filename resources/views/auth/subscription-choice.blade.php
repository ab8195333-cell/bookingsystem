@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <h2 class="fw-bold mb-4">مرحباً بك في نظام الحجوزات</h2>
            <p class="text-muted mb-5">يرجى اختيار خطة البدء للاستمرار</p>

            <div class="row g-4">
                <!-- خيار التجربة المجانية -->
                <div class="col-md-6">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4">
                        <div class="card-body">
                            <i class="fas fa-clock fa-3x text-primary mb-3"></i>
                            <h4 class="fw-bold">تجربة مجانية</h4>
                            <p class="text-muted">استخدم كافة ميزات النظام مجاناً لمدة 5 أيام.</p>
                            
                            <form action="{{ route('subscription.activate.trial') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-lg w-100 rounded-pill fw-bold">
                                    ابدأ التجربة الآن
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- خيار الاشتراك الشهري -->
                <div class="col-md-6">
                    <div class="card h-100 border-success shadow-sm rounded-4 p-4 border-2">
                        <div class="card-body">
                            <i class="fas fa-gem fa-3x text-success mb-3"></i>
                            <h4 class="fw-bold">اشتراك شهري</h4>
                            <p class="text-muted small">دفع 5 دولار شهرياً عبر الكريمي للوصول الدائم.</p>
                            <a href="{{ route('subscription.pay') }}" class="btn btn-success btn-lg w-100 rounded-pill fw-bold">اشترك الآن (5$)</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
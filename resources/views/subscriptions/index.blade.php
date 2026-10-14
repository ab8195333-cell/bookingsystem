@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="fw-bold text-primary">ترقية الحساب والاشتراكات</h1>
        <p class="text-muted fs-5">اختر الخطة المناسبة لنشاطك التجاري للاستفادة الكاملة من ميزات نظام إدارة الحجوزات</p>
    </div>

    <div class="row g-4 justify-content-center">
        <!-- الخطة التجريبية -->
        <div class="col-md-5 col-lg-4">
            <div class="card h-100 shadow-sm border-0 rounded-3">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill">مجاناً</span>
                    </div>
                    <h3 class="card-title fw-bold">الفترة التجريبية</h3>
                    <p class="text-muted small">تجربة النظام واختبار الميزات للبدء الفوري</p>
                    <div class="my-3">
                        <span class="display-5 fw-bold">0</span>
                        <span class="text-muted">ر.ي / مجاناً</span>
                    </div>
                    
                    <ul class="list-unstyled my-4 flex-grow-1">
                        <li class="mb-2"><i class="bi bi-check-lg text-success me-2"></i> إدخال حجوزات محدودة</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success me-2"></i> لوحة تحكم أساسية</li>
                        <li class="mb-2"><i class="bi bi-check-lg text-success me-2"></i> دعم فني عبر البريد</li>
                    </ul>

                    <form action="{{ route('start.trial') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100 py-2 fw-semibold">
                            تفعيل الفترة التجريبية
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- الخطة الاحترافية -->
        <div class="col-md-5 col-lg-4">
            <div class="card h-100 shadow border-primary border-2 rounded-3 position-relative">
                <div class="position-absolute top-0 start-50 translate-middle">
                    <span class="badge bg-primary px-3 py-2 rounded-pill">الأكثر طلباً</span>
                </div>
                <div class="card-body p-4 d-flex flex-column">
                    <div class="mb-3">
                        <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill">احترافي</span>
                    </div>
                    <h3 class="card-title fw-bold">الباقة الشاملة</h3>
                    <p class="text-muted small">كافة الأدوات والميزات بدون قيود لإدارة الحجوزات</p>
                    <div class="my-3">
                        <span class="display-5 fw-bold text-primary">المميزة</span>
                    </div>

                    <ul class="list-unstyled my-4 flex-grow-1">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> عدد غير محدود من الحجوزات</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> تقارير وتحليلات مالية شاملة</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> إدارة العملاء والاشتراكات</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-primary me-2"></i> دعم فني متواصل 24/7</li>
                    </ul>
<!-- الباقة الشاملة (توجيه لدفع إلكتروني أو كريمي) -->
                    <a href="{{ route('subscription.checkout') }}" class="btn btn-primary w-100 py-2 fw-semibold">
                        <i class="bi bi-credit-card me-2"></i> اشترك الآن (الدفع الإلكتروني / الكريمي)
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
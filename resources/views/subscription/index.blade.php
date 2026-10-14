@extends('layouts.app')

@section('content')
<div class="container py-5" dir="rtl">
    <!-- رأس الصفحة -->
    <div class="text-center mb-5">
        <h1 class="fw-bold text-dark">تفعيل واشتراك النظام</h1>
        <p class="text-muted fs-5">لضمان استمرار عمل نظام الحجوزات، يرجى اختيار خطة التفعيل المناسبة لك</p>
    </div>

    <div class="row justify-content-center g-4">
        
        <!-- كرت الخطة التجريبية -->
        <div class="col-md-5">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden text-center border-top border-primary border-4">
                <div class="card-body p-5">
                    <div class="mb-4 text-primary">
                        <i class="fas fa-hourglass-start fa-3x"></i>
                    </div>
                    <h3 class="fw-bold text-dark">فترة تجربة مجانية</h3>
                    <h2 class="text-primary my-4">5 أيام</h2>
                    <hr class="my-4 opacity-50">
                    <ul class="list-unstyled text-end mb-5">
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> الوصول لكافة المميزات</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> إضافة حجوزات بلا حدود</li>
                        <li class="mb-3"><i class="fas fa-check-circle text-success me-2"></i> استخراج تقارير PDF</li>
                    </ul>

                    <!-- زر تفعيل التجربة -->
                    <form action="{{ route('subscription.start_trial') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline-primary w-100 rounded-pill py-3 fw-bold">
                            تفعيل الـ 5 أيام الآن
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- كرت الاشتراك الكامل (كريمي) -->
        <div class="col-md-5">
            <div class="card h-100 shadow border-success rounded-4 overflow-hidden text-center border-top border-success border-4" style="border-width: 1px;">
                <div class="card-body p-5">
                    <div class="mb-4 text-warning">
                        <i class="fas fa-crown fa-3x"></i>
                    </div>
                    <h3 class="fw-bold text-dark">اشتراك كامل (شهري)</h3>
                    <h2 class="text-success my-4">5 دولار <small class="fs-6 text-muted">/ شهر</small></h2>
                    <hr class="my-4 opacity-50">
                    
                    <!-- صندوق بيانات الكريمي -->
                    <div class="bg-light p-3 rounded-4 mb-4 text-end border border-success-subtle">
                        <p class="mb-2 fw-bold text-center text-success">بيانات التحويل عبر بنك الكريمي</p>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">رقم الحساب:</span>
                            <span class="text-dark fw-bold">12345678</span>
                        </div>
                        <div class="d-flex justify-content-between mb-0">
                            <span class="text-muted">الاسم:</span>
                            <span class="text-dark fw-bold">إدارة النظام</span>
                        </div>
                    </div>

                    <!-- زر الواتساب -->
                    <a href="https://wa.me/967XXXXXXXXX?text=أرغب بتفعيل النظام، وهذا إشعار تحويل الـ 5 دولار عبر الكريمي" 
                       target="_blank" 
                       class="btn btn-success w-100 rounded-pill py-3 fw-bold shadow-sm d-flex align-items-center justify-content-center">
                        <i class="fab fa-whatsapp fs-4 me-2"></i> إرسال إشعار الدفع (واتساب)
                    </a>
                    <p class="small text-muted mt-3">سيتم تفعيل حسابك فور استلام إشعار التحويل</p>
                </div>
            </div>
        </div>

    </div>

    <!-- رسائل التنبيه -->
    <div class="row justify-content-center mt-5">
        <div class="col-md-10">
            @if(session('error'))
                <div class="alert alert-danger rounded-pill px-4 text-center">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
                </div>
            @endif
            
            <div class="text-center p-3 text-secondary small">
                <i class="fas fa-shield-alt me-1 text-primary"></i> 
                نظامك مؤمن ومشفر بالكامل. في حال واجهت مشكلة في التفعيل يرجى التواصل مع الدعم الفني.
            </div>
        </div>
    </div>
</div>
@endsection
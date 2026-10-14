@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 text-center pt-5">
                    <div class="mb-3">
                        <i class="fas fa-clock fa-4x text-warning"></i>
                    </div>
                    <h3 class="fw-bold text-dark">انتهت الفترة التجريبية</h3>
                    <p class="text-muted px-4">للاستمرار، يرجى تفعيل الاشتراك الشهري بقيمة 5 دولار.</p>
                </div>

                <div class="card-body p-4">
                    <!-- تفاصيل بيانات الكريمي -->
                    <div class="bg-light rounded-3 p-3 mb-4">
                        <h6 class="fw-bold mb-3 text-secondary text-center">طريقة الدفع (كريمي):</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span>رقم الحساب:</span>
                            <span class="fw-bold text-primary">12345678</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>الاسم:</span>
                            <span class="fw-bold text-dark">عبدالله ماهر</span>
                        </div>
                    </div>

                    <!-- زر الواتساب المصلح برقمك الفعلي -->
                    <a href="https://wa.me/967783046921?text={{ urlencode('مرحباً عبدالله ماهر، لقد قمت بتحويل مبلغ 5 دولار لتفعيل اشتراكي في نظام الحجوزات. اسم المستخدم: ' . auth()->user()->name) }}" 
                       target="_blank" 
                       class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm py-3">
                       <i class="fab fa-whatsapp me-2"></i> إرسال إشعار التحويل (تفعيل)
                    </a>

                    <div class="text-center mt-4">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-link text-decoration-none text-muted small">
                                تسجيل الخروج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 rounded-3 p-4">
                <h3 class="fw-bold mb-3 text-center">اختر طريقة الدفع المفضلة</h3>
                <p class="text-muted text-center mb-4">أنت على وشك الاشتراك في الباقة الشاملة لإدارة الحجوزات.</p>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="d-grid gap-3">
                    <!-- خيار الدفع عبر Stripe (نموذج برلمجي آمن) -->
                    <form action="{{ route('subscription.stripe') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-credit-card fs-4"></i> الدفع عبر بطاقة ائتمان (Stripe)
                        </button>
                    </form>

                    <!-- خيار الدفع/التحويل عبر الكريمي -->
                    <a href="https://wa.me/967733333333?text=مرحباً،%20أرغب%20في%20تفعيل%20الباقة%20الشاملة%20عبر%20الكريمي" 
                       target="_blank" 
                       class="btn btn-success w-100 py-3 fw-semibold d-flex align-items-center justify-content-center gap-2">
                        <i class="bi bi-wallet2 fs-4"></i> التحويل عبر بنك الكريمي / المميز
                    </a>
                </div>

                <div class="mt-4 text-center">
                    <a href="{{ route('subscription.index') }}" class="text-decoration-none text-muted">
                        <i class="bi bi-arrow-right"></i> العودة لاختيار الباقات
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
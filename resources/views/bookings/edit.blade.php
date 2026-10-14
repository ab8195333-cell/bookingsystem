@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-dark text-white rounded-top-4 py-3 text-end">
                    <h5 class="fw-bold mb-0">
                        <i class="fas fa-edit me-2 text-warning"></i> تعديل بيانات الحجز
                    </h5>
                </div>

                <div class="card-body p-4 text-end" dir="rtl">
                    <!-- رسائل التنبيه والخطأ -->
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                            <ul class="mb-0 pe-3">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- نموذج التعديل -->
                    <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- اسم العميل -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">اسم العميل</label>
                            <input type="text" 
                                   name="customer_name" 
                                   class="form-control rounded-3" 
                                   value="{{ old('customer_name', $booking->customer_name) }}" 
                                   required>
                        </div>

                        <!-- الخدمة -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">الخدمة</label>
                            <input type="text" 
                                   name="service_name" 
                                   class="form-control rounded-3" 
                                   value="{{ old('service_name', $booking->service_name) }}" 
                                   required>
                        </div>

                        <!-- المبلغ -->
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">المبلغ (ريال)</label>
                            <input type="number" 
                                   step="0.01" 
                                   name="amount" 
                                   class="form-control rounded-3" 
                                   value="{{ old('amount', $booking->amount) }}" 
                                   required>
                        </div>

                        <!-- تاريخ الحجز -->
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">تاريخ الحجز</label>
                            <input type="date" 
                                   name="booking_date" 
                                   class="form-control rounded-3" 
                                   value="{{ old('booking_date', $booking->booking_date) }}" 
                                   required>
                        </div>

                        <hr class="my-4">

                        <!-- حقل تأكيد كلمة المرور الإدارية -->
                        <div class="mb-4 p-3 bg-light rounded-3 border border-warning">
                            <label class="form-label fw-bold text-danger mb-1">
                                <i class="fas fa-lock me-1"></i> حماية النظام: كلمة مرور المدير للتأكيد
                            </label>
                            <p class="text-muted small mb-2">يرجى إدخال كلمة المرور الخاصة بحسابك لحفظ التعديلات على هذا الحجز.</p>
                            <input type="password" 
                                   name="admin_password" 
                                   class="form-control rounded-3 border-danger-subtle" 
                                   placeholder="أدخل كلمة المرور هنا" 
                                   required>
                        </div>

                        <!-- أزرار التحكم -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                                إلغاء وعودة
                            </a>
                            <button type="submit" class="btn btn-success rounded-pill px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i> حفظ التعديلات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
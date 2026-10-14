@extends('layouts.app')

@section('content')
<div class="container py-4 text-end" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <h4 class="fw-bold mb-4">تعديل بيانات الحجز</h4>

                @if(session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <form action="{{ route('bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label fw-bold">اسم العميل</label>
                        <input type="text" name="customer_name" class="form-control" value="{{ $booking->customer_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">الخدمة</label>
                        <input type="text" name="service_name" class="form-control" value="{{ $booking->service_name }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">المبلغ</label>
                        <input type="number" step="0.01" name="amount" class="form-control" value="{{ $booking->amount }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">التاريخ</label>
                        <input type="date" name="booking_date" class="form-control" value="{{ $booking->booking_date }}" required>
                    </div>

                    <div class="mb-4 bg-light p-3 rounded-3 border">
                        <label class="form-label fw-bold text-danger"><i class="fas fa-lock me-1"></i> كلمة مرور المدير لتأكيد التعديل:</label>
                        <input type="password" name="admin_password" class="form-control" placeholder="أدخل كلمة مرور الحساب" required>
                    </div>

                    <button type="submit" class="btn btn-warning rounded-pill w-100 fw-bold">حفظ التعديلات</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
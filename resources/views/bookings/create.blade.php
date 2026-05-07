@extends('layouts.app')

@section('content')
<style>
    .create-card {
        background: white;
        padding: 40px;
        border-radius: 20px;
        max-width: 600px;
        margin: 40px auto;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }
    .form-control {
        border-radius: 10px;
        padding: 12px;
        border: 1px solid #eee;
        background-color: #fafafa;
        text-align: right;
    }
    .form-control:focus {
        border-color: #007bff;
        box-shadow: none;
        background-color: #fff;
    }
    .btn-save {
        background: #007bff;
        color: white;
        border: none;
        padding: 12px 30px;
        border-radius: 10px;
        width: 100%;
        font-weight: bold;
        font-size: 18px;
        transition: 0.3s;
    }
    .btn-save:hover {
        background: #0056b3;
        transform: translateY(-2px);
    }
</style>

<div class="container" dir="rtl">
    <div class="create-card">
        <h2 class="text-center mb-4" style="font-weight: bold;">إضافة حجز جديد</h2>

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">اسم العميل</label>
                <input type="text" name="customer_name" class="form-control" placeholder="اسم الشخص" required>
            </div>

            <div class="mb-3">
                <label class="form-label">رقم الهاتف</label>
                <input type="text" name="phone_number" class="form-control" placeholder="00967..." required>
            </div>

            <div class="mb-3">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="customer_email" class="form-control" placeholder="email@example.com">
            </div>

            <div class="mb-4">
                <label for="amount" class="form-label" style="font-weight: bold; color: #28a745;">سعر الحجز (المبلغ)</label>
                <input type="number" step="0.01" name="amount" class="form-control" id="amount" 
                       placeholder="مثلاً: 50.00" style="border: 2px solid #e1f5fe;" required>
                <small class="text-muted">أدخل المبلغ الذي سيدفعه العميل مقابل هذا الحجز.</small>
            </div>

            <button type="submit" class="btn-save">حفظ الحجز والسعر</button>
        </form>
    </div>
</div>
@endsection
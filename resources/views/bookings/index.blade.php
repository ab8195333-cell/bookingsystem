@extends('layouts.app')

@section('content')
<style>
    body { background-color: #f8fafc; font-family: 'Cairo', sans-serif; }
    .main-card {
        background: white;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    }
    /* كروت الإحصائيات */
    .stat-box {
        background: white;
        padding: 20px;
        border-radius: 12px;
        border-right: 4px solid #007bff;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
    }
    .stat-label { color: #64748b; font-size: 0.9rem; font-weight: 600; }
    .stat-number { font-size: 1.5rem; font-weight: 700; color: #1e293b; }
    
    /* تنسيق الجدول */
    .table thead th {
        background: #f1f5f9;
        color: #475569;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        border: none;
    }
    .table tbody tr:hover { background-color: #f8fafc; }
    .btn-action { border-radius: 8px; font-weight: 600; transition: 0.2s; }
    .btn-action:hover { transform: translateY(-1px); }
</style>

<div class="container mt-4" dir="rtl">
    
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0">لوحة تحكم الحجوزات</h2>
            <p class="text-muted">أهلاً بك، إليك ملخص نشاطك التجاري اليوم.</p>
        </div>
        <div class="col-md-6 text-md-start">
            <a href="{{ route('bookings.export') }}" class="btn btn-outline-success btn-action shadow-sm me-2">
                📥 تصدير التقرير (Excel)
            </a>
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-action shadow-sm">
                + إضافة حجز جديد
            </a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="stat-label">إجمالي الحجوزات</div>
                <div class="stat-number">{{ $bookings->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box" style="border-right-color: #10b981;">
                <div class="stat-label">إجمالي الأرباح المؤكدة</div>
                <div class="stat-number text-success">{{ number_format($totalEarnings ?? 0, 2) }} ريال</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box" style="border-right-color: #f59e0b;">
                <div class="stat-label">حجوزات قيد الانتظار</div>
                <div class="stat-number text-warning">{{ $bookings->where('status', 'pending')->count() }}</div>
            </div>
        </div>
    </div>

    <div class="main-card">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table align-middle text-center">
                <thead>
                    <tr>
                        <th>العميل</th>
                        <th>بيانات التواصل</th>
                        <th>المبلغ (السعر)</th>
                        <th>الحالة</th>
                        <th>إجراءات الإدارة</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr>
                        <td class="fw-bold text-dark">{{ $booking->customer_name }}</td>
                        <td>
                            <div class="small fw-semibold">{{ $booking->phone_number }}</div>
                            <div class="text-muted" style="font-size: 0.8rem;">{{ $booking->customer_email }}</div>
                        </td>
                        <td class="fw-bold text-success">
                            {{ number_format($booking->amount, 2) }} ريال
                        </td>
                        <td>
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning text-dark px-3 py-2">قيد الانتظار</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success px-3 py-2">مؤكد ✅</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2">ملغي ❌</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-center gap-2">
                                <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button type="submit" class="btn btn-sm btn-outline-success btn-action" title="تأكيد">تأكيد</button>
                                </form>

                                <form action="{{ route('bookings.updateStatus', $booking->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button type="submit" class="btn btn-sm btn-outline-secondary btn-action" title="إلغاء">إلغاء</button>
                                </form>

                                <form action="{{ route('bookings.destroy', $booking->id) }}" method="POST" onsubmit="return confirm('هل تريد حذف هذا السجل نهائياً؟');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger btn-action">حذف</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-3"></i>
                            لا توجد أي حجوزات مسجلة حالياً.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
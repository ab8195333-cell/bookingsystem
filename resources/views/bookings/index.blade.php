@extends('layouts.app')

@section('content')
<!-- الشريط العلوي الداكن (Navbar) -->
<nav class="navbar navbar-dark bg-dark mb-4 shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center py-2">
        <!-- جهة اليمين: الترحيب -->
        <span class="navbar-text text-white fw-bold">
            مرحباً، {{ Auth::user()->name }}
        </span>
        
        <!-- جهة اليسار: أزرار التحكم -->
        <div class="d-flex align-items-center">
            <!-- 1. زر الترقية/الاشتراك المذهب (كريمي) -->
            <a href="{{ route('subscription.index') }}" class="btn btn-warning btn-sm rounded-pill px-3 me-3 fw-bold shadow-sm text-dark">
                <i class="fas fa-crown me-1"></i> ترقية الحساب (كريمي)
            </a>

            <!-- 2. زر تسجيل الخروج -->
            <a href="{{ route('logout') }}" 
               class="btn btn-outline-light btn-sm rounded-pill px-3"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt me-1"></i> خروج
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</nav>

<div class="container py-2">
    <!-- العنوان والأزرار الأساسية -->
    <div class="row mb-4 align-items-center">
        <div class="col text-end">
            <h2 class="fw-bold text-dark">نظام الحجوزات الذكي</h2>
        </div>
        <div class="col-auto d-flex">
            <a href="{{ route('bookings.pdf_all') }}" class="btn btn-danger rounded-pill px-4 shadow-sm me-2">
                <i class="fas fa-file-pdf me-2"></i> تحميل التقرير (PDF)
            </a>
            
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#addBookingModal">
                <i class="fas fa-plus me-2"></i> إضافة حجز جديد
            </button>
        </div>
    </div>

    <!-- رسائل التنبيه للنجاح أو الخطأ -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show text-end" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show text-end" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- كروت الإحصائيات -->
    <div class="row g-4 mb-5 text-end" dir="rtl">
        <div class="col-md-6">
            <div class="card bg-success text-white shadow border-0 rounded-4 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <i class="fas fa-calendar-check fa-3x opacity-25"></i>
                    <div>
                        <h6 class="opacity-75">عدد الحجوزات</h6>
                        <h2 class="fw-bold mb-0">{{ $bookingsCount }} حجز</h2>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-primary text-white shadow border-0 rounded-4 p-2">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <i class="fas fa-money-bill-wave fa-3x opacity-25"></i>
                    <div>
                        <h6 class="opacity-75">إجمالي المبالغ</h6>
                        <h2 class="fw-bold mb-0">{{ number_format($totalAmount, 2) }} ريال</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- الجدول -->
    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0 text-end">
            <div class="table-responsive">
                <table class="table table-hover mb-0 text-center">
                    <thead class="bg-light text-secondary">
                        <tr>
                            <th class="px-4 py-3">العميل</th>
                            <th class="py-3">الخدمة</th>
                            <th class="py-3">المبلغ</th>
                            <th class="py-3">التاريخ</th>
                            <th class="px-4 py-3">الإجراءات المحمية</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        @forelse($bookings as $booking)
                        <tr>
                            <td class="px-4 py-3 fw-bold">{{ $booking->customer_name }}</td>
                            <td>{{ $booking->service_name }}</td>
                            <td>{{ number_format($booking->amount, 2) }} ريال</td>
                            <td>{{ $booking->booking_date }}</td>
                            <td class="px-4 py-3">
                                <!-- أزرار التحكم تظهر فقط للأدمن -->
                                @if(auth()->check() && auth()->user()->role === 'admin')
                                    <div class="d-flex justify-content-center gap-2">
                                        <!-- 1. زر التعديل المحمي بكلمة السر -->
                                        <form action="{{ route('bookings.edit', $booking->id) }}" 
                                              method="GET" 
                                              class="d-inline" 
                                              onsubmit="return confirmAdminPasswordForEdit(event, this);">
                                            <input type="hidden" name="admin_password" class="admin-password-input-edit">
                                            <button type="submit" class="btn btn-outline-warning btn-sm rounded-pill px-3">
                                                <i class="fas fa-edit"></i> تعديل
                                            </button>
                                        </form>

                                        <!-- 2. زر الحذف المحمي بكلمة السر -->
                                        <form action="{{ route('bookings.destroy', $booking->id) }}" 
                                              method="POST" 
                                              class="d-inline" 
                                              onsubmit="return confirmAdminPasswordForDelete(event, this);">
                                            @csrf 
                                            @method('DELETE')
                                            <input type="hidden" name="admin_password" class="admin-password-input-delete">
                                            <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3">
                                                <i class="fas fa-trash"></i> حذف
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">للمدير فقط</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">لا توجد حجوزات مسجلة حالياً</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal إضافة حجز -->
<div class="modal fade" id="addBookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered text-end">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">إضافة حجز جديد</h5>
                <button type="button" class="btn-close ms-0 me-auto" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">اسم العميل</label>
                        <input type="text" name="customer_name" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">الخدمة</label>
                        <input type="text" name="service" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">المبلغ</label>
                        <input type="number" step="0.01" name="amount" class="form-control rounded-3" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">التاريخ</label>
                        <input type="date" name="booking_date" class="form-control rounded-3" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 w-100">حفظ الحجز</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- سكريبت طلب كلمة المرور عند الحذف والتعديل -->
<script>
function confirmAdminPasswordForDelete(event, form) {
    event.preventDefault(); // إيقاف الحذف الفوري

    let password = prompt("⚠️ إجراء محمي! يرجى إدخال كلمة مرور المدير لتأكيد الحذف:");

    if (password !== null && password.trim() !== "") {
        form.querySelector('.admin-password-input-delete').value = password;
        form.submit();
    } else if (password !== null) {
        alert("❌ يجب إدخال كلمة المرور لتنفيذ الحذف!");
    }
}

function confirmAdminPasswordForEdit(event, form) {
    event.preventDefault(); // إيقاف الانتقال الفوري

    let password = prompt("🔒 إجراء محمي! يرجى إدخال كلمة مرور المدير للانتقال لتعديل الحجز:");

    if (password !== null && password.trim() !== "") {
        form.querySelector('.admin-password-input-edit').value = password;
        form.submit();
    } else if (password !== null) {
        alert("❌ يجب إدخال كلمة المرور للانتقال للتعديل!");
    }
}
</script>
@endsection
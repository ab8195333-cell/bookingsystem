@extends('layouts.app')

@section('content')
<div class="container mt-5 text-right" dir="rtl">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">💳 إدارة اشتراكات المستخدمين</h3>
        <span class="badge bg-primary p-2">طلبات التفعيل المعلقة</span>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">اسم المشترك</th>
                            <th class="py-3">البريد الإلكتروني</th>
                            <th class="py-3 text-center">سند التحويل</th>
                            <th class="py-3 text-center">الإجراء</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingUsers as $user)
                        <tr>
                            <td class="px-4 py-3 align-middle font-weight-bold">{{ $user->name }}</td>
                            <td class="py-3 align-middle">{{ $user->email }}</td>
                            <td class="py-3 align-middle text-center">
                                @if($user->payment_receipt)
                                    <a href="{{ asset('storage/' . $user->payment_receipt) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        👁️ عرض صورة السند
                                    </a>
                                @else
                                    <span class="text-muted">لا يوجد سند</span>
                                @endif
                            </td>
                            <td class="py-3 align-middle text-center">
                                <form action="{{ route('admin.subscriptions.activate', $user->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success px-4">
                                        تفعيل الحساب ✅
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <div class="mb-2" style="font-size: 2rem;">📭</div>
                                لا توجد طلبات تفعيل معلقة في الوقت الحالي.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
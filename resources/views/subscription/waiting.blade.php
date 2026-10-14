@extends('layouts.app')

@section('content')
<div class="container mt-5 text-right" dir="rtl">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white font-weight-bold">
                    💳 تفعيل نظام الحجوزات الاحترافي
                </div>
                <div class="card-body">
                    
                    {{-- عرض رسائل النجاح --}}
                    @if(session('success'))
                        <div class="alert alert-success border-0 shadow-sm text-center">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- عرض أخطاء التحقق (مثل اسم مستخدم مكرر أو ملف غير صالح) --}}
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="instructions mb-4">
                        <h5 class="text-primary">خطوات تفعيل حسابك:</h5>
                        <p class="text-muted">للبدء في استخدام النظام، يرجى تحويل الرسوم ورفع صورة السند أدناه:</p>
                        <div class="p-3 bg-light rounded border-right border-primary" style="border-right-width: 5px !important;">
                            <strong>🏦 بنك الكريمي:</strong> 12345678 <br>
                            <strong>📱 محفظة فلوسك:</strong> 777123456
                        </div>
                    </div>

                    <hr>

                    {{-- بداية فورم الرفع --}}
                    <form action="{{ route('upload.receipt') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label font-weight-bold">اختر اسم رابط محلك (Username):</label>
                            <input type="text" name="username" class="form-control @error('username') is-invalid @enderror" 
                                   placeholder="مثلاً: abdullah_salon" required value="{{ old('username') }}">
                            <small class="text-muted">هذا الاسم سيظهر في رابط الحجز الخاص بك (مثلاً: /book/abdullah_salon).</small>
                        </div>

                        <div class="mb-4">
                            <label class="form-label font-weight-bold">ارفع صورة سند التحويل:</label>
                            <input type="file" name="receipt" class="form-control @error('receipt') is-invalid @enderror" required>
                            <small class="text-muted">نقبل ملفات الصور (JPG, PNG) بحد أقصى 2 ميجابايت.</small>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 shadow-sm font-weight-bold">
                            إرسال السند للتفعيل الآن ✅
                        </button>
                    </form>
                    {{-- نهاية فورم الرفع --}}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الحجوزات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Tahoma', sans-serif; padding: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="container">
        <div class="text-center mb-4">
            <h2>نظام الحجوزات الذكي - التقرير الشامل</h2>
            <p class="text-muted">تاريخ التقرير: {{ now()->format('Y-m-d') }}</p>
        </div>

        <div class="row mb-3">
            <div class="col-6"><strong>عدد الحجوزات:</strong> {{ $bookingsCount }}</div>
            <div class="col-6 text-end"><strong>إجمالي المبالغ:</strong> {{ number_format($totalAmount, 2) }} ريال</div>
        </div>

        <table class="table table-bordered text-center">
            <thead class="table-light">
                <tr>
                    <th>العميل</th>
                    <th>الخدمة</th>
                    <th>المبلغ</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                <tr>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ $booking->service_name }}</td>
                    <td>{{ number_format($booking->amount, 2) }} ريال</td>
                    <td>{{ $booking->booking_date }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary px-4">طباعة / حفظ كـ PDF</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary px-4">العودة للنظام</a>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; direction: rtl; text-align: right; color: #333; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #0d6efd; padding-bottom: 10px; }
        .stats { margin-bottom: 20px; width: 100%; }
        .stat-item { width: 48%; display: inline-block; background: #f8f9fa; padding: 10px; border-radius: 5px; border: 1px solid #ddd; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #dee2e6; padding: 12px; text-align: center; }
        th { background-color: #0d6efd; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الحجوزات</h1>
        <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">إجمالي الحجوزات: {{ $bookingsCount }}</div>
        <div class="stat-item">إجمالي المبالغ: {{ number_format($totalAmount, 2) }} ريال</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>العميل</th>
                <th>الخدمة</th>
                <th>المبلغ</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $booking)
            <tr>
                <td>{{ $booking->customer_name }}</td>
                <td>{{ $booking->service_name }}</td>
                <td>{{ number_format($booking->amount, 2) }} ريال</td>
                <td>{{ $booking->booking_date }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; padding: 20px; color: #777;">
                    لا توجد حجوزات مسجلة حالياً
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الحجوزات</title>
    <style>
        body { font-family: 'Amiri', Tahoma, sans-serif; direction: rtl; text-align: right; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>تقرير الحجوزات الشامل</h2>
    <p>إجمالي الحجوزات: {{ $bookingsCount }}</p>
    <p>إجمالي المبالغ: {{ $totalAmount }} ريال</p>

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
            @foreach($bookings as $booking)
            <tr>
                <td>{{ $booking->customer_name }}</td>
                <td>{{ $booking->service_name }}</td>
                <td>{{ $booking->amount }}</td>
                <td>{{ $booking->booking_date }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
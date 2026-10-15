<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>تقرير الحجوزات</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 25px;
            color: #222;
        }

        h1, .summary {
            text-align: center;
        }

        .summary {
            margin: 20px 0;
            line-height: 2;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: right;
        }

        th {
            background: #eee;
        }

        .actions {
            margin: 20px 0;
            text-align: center;
        }

        @media print {
            .actions {
                display: none;
            }
        }
    </style>
</head>
<body>

    <h1>تقرير الحجوزات</h1>

    <div class="summary">
        <div>عدد الحجوزات: {{ $bookingsCount }}</div>
        <div>إجمالي المبالغ: {{ number_format($totalAmount, 2) }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>رقم الحجز</th>
                <th>اسم العميل</th>
                <th>الخدمة</th>
                <th>المبلغ</th>
                <th>تاريخ الحجز</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($bookings as $booking)
                <tr>
                    <td>{{ $booking->id }}</td>
                    <td>{{ $booking->customer_name }}</td>
                    <td>{{ $booking->service_name }}</td>
                    <td>{{ number_format($booking->amount, 2) }}</td>
                    <td>{{ $booking->booking_date }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">لا توجد حجوزات لعرضها.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="actions">
        <button onclick="window.print()">طباعة التقرير</button>
        <button onclick="window.history.back()">رجوع</button>
    </div>

</body>
</html>
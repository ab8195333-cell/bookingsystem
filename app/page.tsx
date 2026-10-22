'use client';
import { useEffect, useState } from 'react';

// تحديد شكل بيانات الحجز (TypeScript Interfaces)
interface Booking {
  id: number;
  title: string;
  amount: number;
  created_at: string;
}

export default function Home() {
  const [bookings, setBookings] = useState<Booking[]>([]);
  const [loading, setLoading] = useState<boolean>(true);

  useEffect(() => {
    // الاتصال برابط الـ API الخاص بلاراول باستخدام المتغير الذي عرفناه
    fetch(`${process.env.NEXT_PUBLIC_API_URL}/bookings`, {
      headers: {
        'Accept': 'application/json',
        // 'Authorization': 'Bearer YOUR_TOKEN' // سنضيف الـ Token لاحقاً عند تسجيل الدخول
      }
    })
      .then((res) => res.json())
      .then((data) => {
        if (data.status) {
          setBookings(data.data);
        }
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error fetching bookings:", err);
        setLoading(false);
      });
  }, []);

  return (
    <main className="p-8 max-w-2xl mx-auto">
      <h1 className="text-3xl font-bold mb-6">نظام إدارة الحجوزات</h1>
      
      {loading ? (
        <p>جاري تحميل البيانات من الـ API...</p>
      ) : bookings.length === 0 ? (
        <p>لا توجد حجوزات حالياً.</p>
      ) : (
        <ul>
          {bookings.map((booking) => (
            <li key={booking.id} className="border p-4 mb-3 rounded shadow-sm">
              <h2 className="font-semibold text-lg">{booking.title}</h2>
              <p>المبلغ: {booking.amount} ريال</p>
              <small className="text-gray-500">{booking.created_at}</small>
            </li>
          ))}
        </ul>
      )}
    </main>
  );
}
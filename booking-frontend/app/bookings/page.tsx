'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';

export default function BookingsPage() {
  const [bookings, setBookings] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';
    const token = localStorage.getItem('token'); // جلب رمز المصادقة إن وجد

    fetch(`${apiUrl}/bookings`, {
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
      },
    })
      .then((res) => {
        if (!res.ok) {
          throw new Error('فشل في جلب البيانات من الخادم');
        }
        return res.json();
      })
      .then((data) => {
        // إذا كانت البيانات تأتي داخل مصفوفة أو كائن معين
        setBookings(Array.isArray(data) ? data : data.bookings || []);
        setLoading(false);
      })
      .catch((err) => {
        console.error(err);
        setError(err.message);
        setLoading(false);
      });
  }, []);

  return (
    <div className="min-h-screen bg-gray-50 p-6 md:p-10" dir="rtl">
      <div className="max-w-6xl mx-auto">
        
        {/* ترويسة الصفحة */}
        <div className="flex justify-between items-center mb-8 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
          <div>
            <h1 className="text-2xl font-bold text-gray-800">إدارة الحجوزات</h1>
            <p className="text-sm text-gray-500 mt-1">عرض ومتابعة كافة الحجوزات المسجلة في النظام</p>
          </div>
          <Link
            href="/bookings/create"
            className="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow transition flex items-center gap-2"
          >
            <span>+ حجز جديد</span>
          </Link>
        </div>

        {/* حالات التحميل والأخطاء */}
        {loading && (
          <div className="text-center py-20 text-gray-500 font-medium animate-pulse">
            جاري تحميل الحجوزات... ⏳
          </div>
        )}

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-center mb-6">
            ❌ حدث خطأ: {error}
          </div>
        )}

        {/* عرض الجدول عند نجاح جلب البيانات */}
        {!loading && !error && (
          <div className="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-right border-collapse">
                <thead>
                  <tr className="bg-gray-50 border-b border-gray-100 text-gray-600 text-xs font-bold uppercase">
                    <th className="py-4 px-6">رقم الحجز</th>
                    <th className="py-4 px-6">اسم العميل</th>
                    <th className="py-4 px-6">التاريخ</th>
                    <th className="py-4 px-6">الحالة</th>
                    <th className="py-4 px-6 text-center">الإجراءات</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-gray-100 text-sm text-gray-700">
                  {bookings.length === 0 ? (
                    <tr>
                      <td colSpan={5} className="py-12 text-center text-gray-400">
                        لا توجد حجوزات مسجلة حالياً.
                      </td>
                    </tr>
                  ) : (
                    bookings.map((booking) => (
                      <tr key={booking.id} className="hover:bg-gray-50/50 transition">
                        <td className="py-4 px-6 font-semibold text-blue-600">#{booking.id}</td>
                        <td className="py-4 px-6">{booking.name || booking.user_name || 'غير متوفر'}</td>
                        <td className="py-4 px-6 text-gray-500">{booking.date || booking.created_at?.substring(0, 10)}</td>
                        <td className="py-4 px-6">
                          <span className={`px-3 py-1 rounded-full text-xs font-semibold ${
                            booking.status === 'confirmed' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'
                          }`}>
                            {booking.status || 'مكتمل'}
                          </span>
                        </td>
                        <td className="py-4 px-6 text-center">
                          <button className="text-gray-400 hover:text-blue-600 font-medium text-xs bg-gray-100 px-3 py-1.5 rounded-lg transition">
                            التفاصيل
                          </button>
                        </td>
                      </tr>
                    ))
                  )}
                </tbody>
              </table>
            </div>
          </div>
        )}

      </div>
    </div>
  );
}
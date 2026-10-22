'use client';

import { useEffect, useState } from 'react';
import Link from 'next/link';

export default function BookingsPage() {
  const [bookings, setBookings] = useState<any[]>([]);
  const [bookingsCount, setBookingsCount] = useState<number>(0);
  const [totalAmount, setTotalAmount] = useState<number>(0);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const fetchBookings = async () => {
    setLoading(true);
    setError(null);
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';
    const token = localStorage.getItem('token');

    try {
      const res = await fetch(`${apiUrl}/bookings`, {
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        },
      });

      const json = await res.json();

      if (!res.ok) {
        if (res.status === 403 && json.status === 'subscription_required') {
          throw new Error('⚠️ انتهت الفترة التجريبية لحسابك، يرجى التفعيل لمتابعة استخدام النظام.');
        }
        throw new Error(json.message || 'فشل في جلب الحجوزات');
      }

      if (json.status === 'success' && json.data) {
        setBookings(json.data.bookings || []);
        setBookingsCount(json.data.bookingsCount || 0);
        setTotalAmount(parseFloat(json.data.totalAmount) || 0);
      }
    } catch (err: any) {
      console.error('Fetch error:', err);
      setError(err.message || 'حدث خطأ في الاتصال بالخادم');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchBookings();
  }, []);

  return (
    <div className="min-h-screen bg-slate-100 p-6 md:p-10 font-sans" dir="rtl">
      <div className="max-w-6xl mx-auto space-y-6">
        
        {/* الترويسة */}
        <div className="flex justify-between items-center bg-white p-6 rounded-2xl shadow-sm border border-slate-200">
          <div>
            <h1 className="text-2xl font-black text-slate-800">نظام الحجوزات الذكي 🚀</h1>
            <p className="text-sm text-slate-500 mt-1 font-medium">عرض ومتابعة كافة الحجوزات والتقرير المالي</p>
          </div>
          <Link
            href="/bookings/create"
            className="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition flex items-center gap-2"
          >
            <span>+ إضافة حجز جديد</span>
          </Link>
        </div>

        {/* كروت الإحصائيات الجاهزة من الباك إند */}
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div className="bg-blue-600 text-white p-6 rounded-2xl shadow-md flex items-center justify-between">
            <div>
              <p className="text-sm font-bold opacity-90 mb-1">إجمالي المبالغ</p>
              <h2 className="text-3xl font-black">{totalAmount.toFixed(2)} ريال</h2>
            </div>
            <div className="text-4xl opacity-80">💰</div>
          </div>

          <div className="bg-emerald-600 text-white p-6 rounded-2xl shadow-md flex items-center justify-between">
            <div>
              <p className="text-sm font-bold opacity-90 mb-1">عدد الحجوزات</p>
              <h2 className="text-3xl font-black">{bookingsCount} حجز</h2>
            </div>
            <div className="text-4xl opacity-80">📅</div>
          </div>
        </div>

        {/* التنبيهات والأخطاء */}
        {loading && (
          <div className="bg-white p-12 rounded-2xl text-center text-slate-600 font-bold shadow-sm border border-slate-200 animate-pulse">
            جاري تحميل وقراءة البيانات من الخادم... ⏳
          </div>
        )}

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-center font-bold">
            {error}
          </div>
        )}

        {/* الجدول */}
        {!loading && !error && (
          <div className="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div className="overflow-x-auto">
              <table className="w-full text-right border-collapse">
                <thead>
                  <tr className="bg-slate-50 border-b border-slate-200 text-slate-700 text-sm font-black">
                    <th className="py-4 px-6">العميل</th>
                    <th className="py-4 px-6">الخدمة</th>
                    <th className="py-4 px-6">المبلغ</th>
                    <th className="py-4 px-6">التاريخ</th>
                  </tr>
                </thead>
                <tbody className="divide-y divide-slate-100 text-sm text-slate-800 font-medium">
                  {bookings.length === 0 ? (
                    <tr>
                      <td colSpan={4} className="py-12 text-center text-slate-400 font-bold">
                        لا توجد حجوزات مسجلة حالياً.
                      </td>
                    </tr>
                  ) : (
                    bookings.map((b, idx) => (
                      <tr key={b.id || idx} className="hover:bg-slate-50 transition">
                        <td className="py-4 px-6 font-bold text-slate-900">{b.customer_name}</td>
                        <td className="py-4 px-6 text-slate-600">{b.service_name}</td>
                        <td className="py-4 px-6 font-black text-emerald-600">
                          {parseFloat(b.amount).toFixed(2)} ريال
                        </td>
                        <td className="py-4 px-6 text-slate-500 dir-ltr text-right">{b.booking_date}</td>
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
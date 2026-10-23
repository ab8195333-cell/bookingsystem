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
    
    // الرابط المباشر لسيرفر لارافيل
    
    // استخدم الرابط المباشر لسيرفر لارافيل على Render
const apiUrl = 'https://bookingsystem-krms.onrender.com/api';
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;

    try {
      const res = await fetch(`${apiUrl}/bookings`, {
        method: 'GET',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        },
      });

      const contentType = res.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('تعذر جلب البيانات. سيرفر الباك إند غير متاح حالياً أو يستعد للتشغيل.');
      }

      const json = await res.json();

      if (!res.ok) {
        if (res.status === 401 || json.message === 'Unauthenticated.') {
          throw new Error('🔑 غير مصرح: يرجى تسجيل الدخول أولاً لرؤية الحجوزات.');
        }
        if (res.status === 403 && json.status === 'subscription_required') {
          throw new Error('⚠️ انتهت الفترة التجريبية لحسابك، يرجى تفعيل الاشتراك لمتابعة الاستخدام.');
        }
        throw new Error(json.message || 'فشل في جلب الحجوزات');
      }

      if (json.status === 'success' && json.data) {
        setBookings(json.data.bookings || []);
        setBookingsCount(json.data.bookingsCount || 0);
        setTotalAmount(parseFloat(json.data.totalAmount) || 0);
      } else if (Array.isArray(json)) {
        setBookings(json);
        setBookingsCount(json.length);
        const total = json.reduce((acc: number, item: any) => acc + (parseFloat(item.amount) || 0), 0);
        setTotalAmount(total);
      }
    } catch (err: any) {
      console.error('Fetch error:', err);
      setError(err.message || 'حدث خطأ في الاتصال بالخادم (Failed to fetch)');
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
          <div className="flex gap-3">
            <button
              onClick={fetchBookings}
              className="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition text-sm"
            >
              🔄 تحديث
            </button>
            <Link
              href="/bookings/create"
              className="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-600/20 transition flex items-center gap-2 text-sm"
            >
              <span>+ إضافة حجز جديد</span>
            </Link>
          </div>
        </div>

        {/* كروت الإحصائيات */}
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

        {/* حالة التحميل والتنبيهات */}
        {loading && (
          <div className="bg-white p-12 rounded-2xl text-center text-slate-600 font-bold shadow-sm border border-slate-200 animate-pulse">
            جاري جلب بيانات الحجوزات من الخادم... ⏳
          </div>
        )}

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-600 p-5 rounded-2xl text-center font-bold flex flex-col items-center gap-3">
            <span>❌ {error}</span>
            {error.includes('تسجيل الدخول') && (
              <Link href="/login" className="px-5 py-2 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition">
                الانتقال لصفحة تسجيل الدخول ←
              </Link>
            )}
          </div>
        )}

        {/* جدول الحجوزات */}
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
                        <td className="py-4 px-6 font-bold text-slate-900">{b.customer_name || b.name}</td>
                        <td className="py-4 px-6 text-slate-600">{b.service_name || b.service}</td>
                        <td className="py-4 px-6 font-black text-emerald-600">
                          {parseFloat(b.amount || b.price || 0).toFixed(2)} ريال
                        </td>
                        <td className="py-4 px-6 text-slate-500 dir-ltr text-right">{b.booking_date || b.date}</td>
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
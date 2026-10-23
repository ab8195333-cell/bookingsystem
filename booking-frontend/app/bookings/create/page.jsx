'use client';

import { useState, FormEvent, ChangeEvent } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function CreateBookingPage() {
  const [formData, setFormData] = useState({
    name: '',
    service: '',
    price: '',
    date: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    // تأكد من أن هذا الرابط هو رابط الباك إند الصحيح الخاص بك على Render
    const apiUrl = 'https://bookingsystem-krms.onrender.com/api';
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;

    if (!token) {
      setError('⚠️ يرجى تسجيل الدخول أولاً قبل محاولة إضافة الحجز.');
      setLoading(false);
      return;
    }

    const payload = {
      customer_name: formData.name,
      service: formData.service,
      amount: parseFloat(formData.price) || 0,
      booking_date: formData.date,
    };

    try {
      const response = await fetch(`${apiUrl}/bookings`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'Authorization': `Bearer ${token}`,
        },
        body: JSON.stringify(payload),
      });

      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('الخادم لم يستجب بصيغة JSON. تأكد من صحة رابط الباك إند وخلوه من أخطاء السيرفر.');
      }

      const data = await response.json();

      if (!response.ok) {
        if (response.status === 401) {
          throw new Error('🔑 انتهت صلاحية الجلسة، يرجى إعادة تسجيل الدخول.');
        }
        if (response.status === 403 && data.status === 'subscription_required') {
          throw new Error('⚠️ انتهت الفترة التجريبية، يرجى تفعيل الاشتراك لإضافة حجوزات جديدة.');
        }
        if (data.errors) {
          const errorMessages = Object.values(data.errors).flat().join(' | ');
          throw new Error(errorMessages);
        }
        throw new Error(data.message || 'فشل في حفظ الحجز من الخادم');
      }

      // النجاح والانتقال لقائمة الحجوزات
      router.push('/bookings');
    } catch (err: unknown) {
      console.error('Save booking error:', err);
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError('تعذر الاتصال بالخادم. تأكد من أن سيرفر الباك إند يعمل ومتصل بالإنترنت.');
      }
    } finally {
      setLoading(false);
    }
  };

  const handleInputChange = (e: ChangeEvent<HTMLInputElement>, field: string) => {
    setFormData({ ...formData, [field]: e.target.value });
  };

  return (
    <div className="min-h-screen bg-slate-100 p-6 md:p-10 flex items-center justify-center font-sans" dir="rtl">
      <div className="max-w-xl w-full bg-white p-8 rounded-3xl shadow-xl border border-slate-200">
        
        <div className="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
          <h1 className="text-2xl font-black text-slate-800">إضافة حجز جديد</h1>
          <Link href="/bookings" className="text-sm font-semibold text-blue-600 hover:text-blue-800">
            ← العودة للحجوزات
          </Link>
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-sm mb-5 font-bold leading-relaxed flex flex-col gap-2">
            <span>❌ {error}</span>
            {error.includes('تسجيل الدخول') && (
              <Link href="/login" className="underline font-black text-blue-700">
                الذهاب لصفحة تسجيل الدخول ←
              </Link>
            )}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">اسم العميل</label>
            <input
              type="text"
              required
              value={formData.name}
              onChange={(e) => handleInputChange(e, 'name')}
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="أدخل اسم العميل"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">الخدمة</label>
            <input
              type="text"
              required
              value={formData.service}
              onChange={(e) => handleInputChange(e, 'service')}
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="أدخل اسم الخدمة"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">المبلغ (ريال)</label>
            <input
              type="number"
              step="0.01"
              required
              value={formData.price}
              onChange={(e) => handleInputChange(e, 'price')}
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="0.00"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">تاريخ الحجز</label>
            <input
              type="date"
              required
              value={formData.date}
              onChange={(e) => handleInputChange(e, 'date')}
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
            />
          </div>

          <div className="flex gap-4 pt-4">
            <button
              type="submit"
              disabled={loading}
              className="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-blue-600/20 disabled:opacity-50"
            >
              {loading ? 'جاري الحفظ...' : 'حفظ الحجز'}
            </button>
            <Link
              href="/bookings"
              className="px-6 py-3.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold rounded-xl transition text-center"
            >
              إلغاء
            </Link>
          </div>
        </form>

      </div>
    </div>
  );
}
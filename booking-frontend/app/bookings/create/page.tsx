'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function CreateBookingPage() {
  const [formData, setFormData] = useState({
    name: '',
    service: '',
    price: '',
    date: '',
    status: 'confirmed',
  });

  const [loading, setLoading] = useState(false);
  const [success, setSuccess] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);
    setSuccess(false);

    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;

    // إرسال الحقول بكلا التسميتين لضمان قبول Validation لارافيل
    const parsedAmount = parseFloat(formData.price);
    const validAmount = isNaN(parsedAmount) ? 10 : parsedAmount;

    const payload = {
      customer_name: formData.name,
      service: formData.service || 'خدمة عامة',
      service_name: formData.service || 'خدمة عامة',
      amount: validAmount,
      price: validAmount,
      booking_date: formData.date,
      appointment_date: formData.date ? `${formData.date} 00:00:00` : new Date().toISOString().slice(0, 10),
      status: formData.status || 'confirmed',
    };

    try {
      const response = await fetch(`${apiUrl}/bookings`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        },
        body: JSON.stringify(payload),
      });

      const data = await response.json();

      if (!response.ok) {
        if (data.errors) {
          const errorMessages = Object.values(data.errors).flat().join(' | ');
          throw new Error(errorMessages);
        }
        throw new Error(data.message || data.error || 'فشل في حفظ الحجز');
      }

      setSuccess(true);
      setTimeout(() => {
        router.push('/bookings');
      }, 1200);

    } catch (err: any) {
      console.error('Save booking error:', err);
      setError(err.message || 'حدث خطأ أثناء حفظ الحجز.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-100 flex items-center justify-center p-6">
      <div className="w-full max-w-xl bg-white rounded-3xl shadow-xl p-8 md:p-10 border border-slate-200">
        
        {/* ترويسة النموذج */}
        <div className="flex justify-between items-center mb-8 border-b border-slate-100 pb-4">
          <h2 className="text-2xl font-bold text-slate-800">إضافة حجز جديد 📋</h2>
          <Link href="/bookings" className="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
            ← العودة للحجوزات
          </Link>
        </div>

        {/* تنبيه النجاح */}
        {success && (
          <div className="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-bold text-center animate-pulse">
            ✅ تم حفظ الحجز في قاعدة البيانات بنجاح! جاري تحويلك...
          </div>
        )}

        {/* تنبيه الخطأ */}
        {error && (
          <div className="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-semibold">
            ❌ {error}
          </div>
        )}

        {/* نموذج الإدخال */}
        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">اسم العميل</label>
            <input
              type="text"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="أدخل اسم العميل"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">الخدمة المطلوبة</label>
            <input
              type="text"
              value={formData.service}
              onChange={(e) => setFormData({ ...formData, service: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="أدخل اسم الخدمة"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">المبلغ (ريال)</label>
            <input
              type="number"
              step="0.01"
              value={formData.price}
              onChange={(e) => setFormData({ ...formData, price: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="100"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">تاريخ الحجز</label>
            <input
              type="date"
              value={formData.date}
              onChange={(e) => setFormData({ ...formData, date: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
            />
          </div>

          <button
            type="submit"
            disabled={loading || success}
            className="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold p-4 rounded-2xl shadow-lg transition duration-200 disabled:opacity-50 text-base"
          >
            {loading ? 'جاري حفظ الحجز...' : success ? 'تم الحفظ ✅' : 'حفظ الحجز في النظام'}
          </button>
        </form>
      </div>
    </div>
  );
}
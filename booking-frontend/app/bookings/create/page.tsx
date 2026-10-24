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
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';
    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;

    const payload = {
      customer_name: formData.name,
      service_name: formData.service || 'خدمة عامة',
      amount: parseFloat(formData.price) || 0,
      booking_date: formData.date,
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
        throw new Error(data.message || 'فشل في حفظ الحجز');
      }

      router.push('/bookings');
    } catch (err: any) {
      console.error('Save booking error:', err);
      setError(err.message || 'حدث خطأ أثناء حفظ الحجز.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-900 flex items-center justify-center p-4">
      <div className="w-full max-w-lg bg-white rounded-2xl shadow-2xl p-8 border border-slate-700">
        
        {/* ترويسة النموذج */}
        <div className="flex justify-between items-center mb-6 border-b pb-4">
          <h2 className="text-2xl font-bold text-slate-900">إضافة حجز جديد</h2>
          <Link href="/bookings" className="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
            ← العودة للحجوزات
          </Link>
        </div>

        {/* صندوق التنبيه بالأخطاء */}
        {error && (
          <div className="mb-4 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm font-semibold">
            ❌ {error}
          </div>
        )}

        {/* نموذج الإدخال */}
        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">اسم العميل</label>
            <input
              type="text"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="أدخل اسم العميل"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">الخدمة</label>
            <input
              type="text"
              value={formData.service}
              onChange={(e) => setFormData({ ...formData, service: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
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
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="0.00"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">تاريخ الحجز</label>
            <input
              type="date"
              value={formData.date}
              onChange={(e) => setFormData({ ...formData, date: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full mt-4 bg-blue-600 hover:bg-blue-700 text-white font-bold p-4 rounded-xl shadow-lg transition duration-200 disabled:opacity-50"
          >
            {loading ? 'جاري الحفظ...' : 'حفظ الحجز'}
          </button>
        </form>
      </div>
    </div>
  );
}
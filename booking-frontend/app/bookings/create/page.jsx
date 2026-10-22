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
  const [error, setError] = useState(null);
  const router = useRouter();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';
    const token = localStorage.getItem('token');

    try {
      const response = await fetch(`${apiUrl}/bookings`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        },
        body: JSON.stringify(formData),
      });

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'فشل في حفظ الحجز');
      }

      router.push('/bookings');
    } catch (err) {
      setError(err.message);
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-50 p-6 md:p-10 flex items-center justify-center" dir="rtl">
      <div className="max-w-xl w-full bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
        
        <div className="flex justify-between items-center mb-6 border-b pb-4">
          <h1 className="text-xl font-bold text-gray-800">إضافة حجز جديد</h1>
          <Link href="/bookings" className="text-sm text-gray-500 hover:text-gray-700">
            ← العودة للحجوزات
          </Link>
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-600 p-3 rounded-xl text-sm mb-4">
            {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">اسم العميل</label>
            <input
              type="text"
              required
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              className="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-blue-600"
              placeholder="أدخل اسم العميل"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">الخدمة</label>
            <input
              type="text"
              required
              value={formData.service}
              onChange={(e) => setFormData({ ...formData, service: e.target.value })}
              className="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-blue-600"
              placeholder="أدخل اسم الخدمة"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">المبلغ (ريال)</label>
            <input
              type="number"
              step="0.01"
              required
              value={formData.price}
              onChange={(e) => setFormData({ ...formData, price: e.target.value })}
              className="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-blue-600"
              placeholder="0.00"
            />
          </div>

          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">تاريخ الحجز</label>
            <input
              type="date"
              required
              value={formData.date}
              onChange={(e) => setFormData({ ...formData, date: e.target.value })}
              className="w-full border border-gray-200 rounded-xl p-3 text-sm focus:outline-none focus:border-blue-600"
            />
          </div>

          <div className="flex gap-4 pt-4">
            <button
              type="submit"
              disabled={loading}
              className="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 rounded-xl transition shadow disabled:opacity-50"
            >
              {loading ? 'جاري الحفظ...' : 'حفظ الحجز'}
            </button>
            <Link
              href="/bookings"
              className="px-5 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-xl transition text-center"
            >
              إلغاء
            </Link>
          </div>
        </form>

      </div>
    </div>
  );
}
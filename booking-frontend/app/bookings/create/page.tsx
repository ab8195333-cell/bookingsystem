'use client';

import { useState, FormEvent } from 'react';
import { useRouter } from 'next/navigation';

export default function CreateBookingPage() {
  const router = useRouter();

  const [formData, setFormData] = useState({
    name: '',
    service: '',
    price: '',
    date: '',
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLSelectElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const token = typeof window !== 'undefined' ? localStorage.getItem('token') : null;

    if (!token) {
      setError('⚠️ يرجى تسجيل الدخول أولاً قبل محاولة إضافة الحجز');
      setLoading(false);
      return;
    }

    // كتابة الرابط المباشر للـ Backend
    const apiUrl = 'https://bookingsystem-krms.onrender.com/api';

    // تجهيز الـ Payload بالحقول الصحيحة فقط المطابقة لـ Laravel
    const payload = {
      customer_name: formData.name,
      service_name: formData.service || 'خدمة عامة',
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

      const data = await response.json();

      if (!response.ok) {
        throw new Error(data.message || 'حدث خطأ أثناء حفظ الحجز في السيرفر');
      }

      // النجاح والانتقال لصفحة الحجوزات
      router.push('/bookings');

    } catch (err: any) {
      setError(err.message || 'فشل الاتصال بالسيرفر');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md">
      <h2 className="text-2xl font-bold text-center mb-6 text-gray-800">إضافة حجز جديد</h2>

      {error && (
        <div className="mb-4 p-3 bg-red-100 text-red-700 rounded-md text-sm font-medium">
          {error}
        </div>
      )}

      <form onSubmit={handleSubmit} className="space-y-4">
        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">اسم العميل</label>
          <input
            type="text"
            name="name"
            value={formData.name}
            onChange={handleChange}
            required
            className="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="مثال: عبدالله ماهر"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">الخدمة</label>
          <input
            type="text"
            name="service"
            value={formData.service}
            onChange={handleChange}
            required
            className="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="مثال: حجز قاعة"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">المبلغ (ريال)</label>
          <input
            type="number"
            name="price"
            value={formData.price}
            onChange={handleChange}
            required
            step="0.01"
            className="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="0.00"
          />
        </div>

        <div>
          <label className="block text-sm font-medium text-gray-700 mb-1">تاريخ الحجز</label>
          <input
            type="date"
            name="date"
            value={formData.date}
            onChange={handleChange}
            required
            className="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          />
        </div>

        <button
          type="submit"
          disabled={loading}
          className="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-md shadow focus:outline-none disabled:opacity-50 transition duration-200"
        >
          {loading ? 'جاري الحفظ...' : 'حفظ الحجز'}
        </button>
      </form>
    </div>
  );
}
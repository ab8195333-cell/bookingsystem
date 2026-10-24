'use client';

import { useEffect, useState, use } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function EditBookingPage({ params }: { params: Promise<{ id: string }> }) {
  const resolvedParams = use(params);
  const id = resolvedParams.id;

  const [formData, setFormData] = useState({
    name: '',
    service: '',
    price: '',
    date: '',
    status: 'confirmed',
  });

  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState(false);
  const router = useRouter();

  const apiUrl = 'https://bookingsystem-krms.onrender.com/api';

  // جلب بيانات الحجز القديمة لتعبئة الحقول
  useEffect(() => {
    const fetchBooking = async () => {
      try {
        const res = await fetch(`${apiUrl}/bookings/${id}`);
        const json = await res.json();

        if (!res.ok) throw new Error(json.message || 'فشل في جلب بيانات الحجز');

        const bookingData = json.data || json;
        setFormData({
          name: bookingData.customer_name || bookingData.name || '',
          service: bookingData.service_name || bookingData.service || '',
          price: bookingData.amount || bookingData.price || '',
          date: bookingData.booking_date || bookingData.date || '',
          status: bookingData.status || 'confirmed',
        });
      } catch (err: any) {
        setError(err.message);
      } finally {
        setLoading(false);
      }
    };

    fetchBooking();
  }, [id]);

  // إرسال التعديلات
  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setSaving(true);
    setError(null);

    const payload = {
      customer_name: formData.name,
      service_name: formData.service,
      amount: parseFloat(formData.price) || 0,
      booking_date: formData.date,
      appointment_date: formData.date ? `${formData.date} 00:00:00` : null,
      status: formData.status,
    };

    try {
      const res = await fetch(`${apiUrl}/bookings/${id}`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify(payload),
      });

      const json = await res.json();
      if (!res.ok) throw new Error(json.message || 'فشل في تحديث الحجز');

      setSuccess(true);
      setTimeout(() => {
        router.push('/bookings');
      }, 1200);
    } catch (err: any) {
      setError(err.message || 'حدث خطأ أثناء التحديث');
    } finally {
      setSaving(false);
    }
  };

  if (loading) {
    return <div className="min-h-screen bg-slate-100 flex items-center justify-center font-bold text-slate-600">جاري تحميل بيانات الحجز... ⏳</div>;
  }

  return (
    <div className="min-h-screen bg-slate-100 flex items-center justify-center p-6" dir="rtl">
      <div className="w-full max-w-xl bg-white rounded-3xl shadow-xl p-8 md:p-10 border border-slate-200">
        
        <div className="flex justify-between items-center mb-8 border-b border-slate-100 pb-4">
          <h2 className="text-2xl font-bold text-slate-800">تعديل الحجز ✏️</h2>
          <Link href="/bookings" className="text-sm font-semibold text-blue-600 hover:text-blue-800 transition">
            ← العودة للحجوزات
          </Link>
        </div>

        {success && (
          <div className="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-2xl text-sm font-bold text-center">
            ✅ تم تحديث الحجز بنجاح! جاري تحويلك...
          </div>
        )}

        {error && (
          <div className="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-2xl text-sm font-semibold">
            ❌ {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-6">
          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">اسم العميل</label>
            <input
              type="text"
              value={formData.name}
              onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              required
              className="w-full bg-slate-50 border border-slate-300 rounded-2xl p-4 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
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
            disabled={saving || success}
            className="w-full mt-6 bg-blue-600 hover:bg-blue-700 text-white font-bold p-4 rounded-2xl shadow-lg transition duration-200 disabled:opacity-50 text-base"
          >
            {saving ? 'جاري حفظ التعديلات...' : success ? 'تم التحديث ✅' : 'حفظ التعديلات'}
          </button>
        </form>
      </div>
    </div>
  );
}
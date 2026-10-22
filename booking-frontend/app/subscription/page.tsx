'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';

export default function SubscriptionPage() {
  const [loading, setLoading] = useState(false);
  const router = useRouter();

  // رابط الباك إند من متغيرات البيئة
  const API_URL = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';

  // تفعيل التجربة المجانية 5 أيام عبر الاتصال بالـ Backend
  const handleFreeTrial = async () => {
    setLoading(true);

    try {
      // جلب الـ token الخاص بالمستخدم المسجل
      const token = localStorage.getItem('token');

      const response = await fetch(`${API_URL}/subscribe/trial`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${token}`,
          'Accept': 'application/json',
        },
      });

      const data = await response.json();

      if (response.ok) {
        alert('تم تفعيل التجربة المجانية بنجاح! 🚀');
        router.push('/bookings'); // التوجيه إلى صفحة الحجوزات
      } else {
        // في حال تم تفعيل التجربة مسبقاً أو حدث خطأ
        alert(data.message || 'حدث خطأ أثناء تفعيل التجربة المجانية.');
        router.push('/bookings');
      }
    } catch (error) {
      console.error('Error starting free trial:', error);
      // التوجيه المؤقت لصفحة الحجوزات
      router.push('/bookings');
    } finally {
      setLoading(false);
    }
  };

  // تفعيل الاشتراك المدفوع 5$ عبر بوابة الدفع / Script
  const handlePaidSubscription = () => {
    alert('سيتم تحويلك الآن إلى بوابة الدفع الآمنة');
    // يمكنك وضع رابط بوابة الدفع الخاصة بك هنا مستقبلاً
  };

  return (
    <div className="min-h-screen bg-gray-50 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" dir="rtl">
      <div className="max-w-3xl w-full text-center">
        
        <h2 className="text-3xl font-extrabold text-gray-900 mb-2">اختر خطة الاشتراك المناسبة لك</h2>
        <p className="text-gray-500 mb-10">ابدأ الآن واستمتع بكافة مميزات نظام الحجوزات</p>

        <div className="grid grid-cols-1 md:grid-cols-2 gap-8">
          
          {/* خيار التجربة المجانية */}
          <div className="bg-white p-8 rounded-3xl shadow-lg border border-gray-100 flex flex-col justify-between hover:shadow-xl transition">
            <div>
              <span className="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full uppercase">تجريبي</span>
              <h3 className="text-2xl font-bold text-gray-800 mt-4">تجربة مجانية</h3>
              <p className="text-gray-400 text-sm mt-2">استكشف النظام كاملاً بدون أي رسوم</p>
              <div className="my-6">
                <span className="text-4xl font-extrabold text-gray-900">0$</span>
                <span className="text-gray-500"> / لمدة 5 أيام</span>
              </div>
            </div>
            <button
              onClick={handleFreeTrial}
              disabled={loading}
              className="w-full py-3 bg-gray-900 hover:bg-gray-800 text-white font-bold rounded-xl transition shadow disabled:opacity-50"
            >
              {loading ? 'جاري التفعيل...' : 'ابدأ التجربة المجانية'}
            </button>
          </div>

          {/* خيار الاشتراك المدفوع 5$ */}
          <div className="bg-white p-8 rounded-3xl shadow-lg border-2 border-blue-600 flex flex-col justify-between hover:shadow-xl transition relative">
            <div className="absolute -top-3 right-1/2 translate-x-1/2 bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full">
              الأكثر طلباً
            </div>
            <div>
              <span className="bg-blue-100 text-blue-700 text-xs font-bold px-3 py-1 rounded-full uppercase">شهري</span>
              <h3 className="text-2xl font-bold text-gray-800 mt-4">اشتراك احترافي</h3>
              <p className="text-gray-400 text-sm mt-2">وصول غير محدود لكافة صلاحيات الحجوزات</p>
              <div className="my-6">
                <span className="text-4xl font-extrabold text-blue-600">$5</span>
                <span className="text-gray-500"> / شهرياً</span>
              </div>
            </div>
            <button
              onClick={handlePaidSubscription}
              className="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition shadow"
            >
              اشترك الآن (عبر Script)
            </button>
          </div>

        </div>

      </div>
    </div>
  );
}
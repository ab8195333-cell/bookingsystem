'use client';

import Link from 'next/link';

export default function Home() {
  return (
    <div className="flex flex-col min-h-screen items-center justify-center bg-slate-100 font-sans p-6" dir="rtl">
      <main className="flex w-full max-w-2xl flex-col items-center justify-between p-10 bg-white rounded-3xl shadow-xl border border-slate-200 text-center">
        
        {/* العنوان الرئيسي */}
        <div className="mb-8">
          <h1 className="text-3xl font-black text-slate-800 mb-2">
            نظام إدارة الحجوزات الذكي 🚀
          </h1>
          <p className="text-slate-500 font-medium">
            الواجهة الأمامية متصلة بنجاح مع خادم الخلفية (Laravel API)
          </p>
        </div>

        {/* تنبيه حالة النظام */}
        <div className="w-full bg-emerald-50 border border-emerald-200 text-emerald-700 p-5 rounded-2xl font-bold mb-8">
          ✨ النظام يعمل بكفاءة وجاهز لاستقبال العمليات!
        </div>

        {/* الأزرار التوجيهية */}
        <div className="flex flex-col sm:flex-row gap-4 w-full justify-center">
          <Link
            href="/login"
            className="flex-1 h-12 items-center justify-center flex bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition"
          >
            🔑 تسجيل الدخول
          </Link>
          
          <Link
            href="/bookings"
            className="flex-1 h-12 items-center justify-center flex bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-2xl transition"
          >
            📊 عرض الحجوزات
          </Link>
        </div>

        {/* رابط إضافي */}
        <div className="mt-6">
          <Link
            href="/bookings/create"
            className="text-sm font-bold text-blue-600 hover:underline"
          >
            + إضافة حجز جديد مباشرة
          </Link>
        </div>

      </main>
    </div>
  );
}
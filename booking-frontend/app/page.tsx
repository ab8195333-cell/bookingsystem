'use client';

import { useEffect, useState } from 'react';

export default function Home() {
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    // جلب رابط API من متغير البيئة أو استخدام الرابط المباشر
    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';

    fetch(apiUrl)
      .then((res) => {
        if (!res.ok) {
          throw new Error(`خطأ في الاستجابة: ${res.status}`);
        }
        return res.json();
      })
      .then((result) => {
        setData(result);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Error fetching API:", err);
        setError(err.message);
        setLoading(false);
      });
  }, []);

  return (
    <div className="flex flex-col min-h-screen items-center justify-center bg-zinc-50 font-sans dark:bg-zinc-900 p-6">
      <main className="flex w-full max-w-3xl flex-col items-center justify-between p-8 bg-white dark:bg-zinc-800 rounded-2xl shadow-lg border border-zinc-200 dark:border-zinc-700">
        
        {/* العنوان الرئيسي */}
        <div className="text-center mb-8">
          <h1 className="text-3xl font-bold text-zinc-900 dark:text-white mb-2">
            نظام إدارة الحجوزات 🚀
          </h1>
          <p className="text-zinc-600 dark:text-zinc-300">
            الواجهة الأمامية (Next.js) متصلة بالخادم الخلفي (Laravel API)
          </p>
        </div>

        {/* حالة الاتصال بالـ Backend */}
        <div className="w-full bg-zinc-100 dark:bg-zinc-950 p-6 rounded-xl border border-zinc-200 dark:border-zinc-800 text-center">
          {loading && (
            <div className="flex items-center justify-center gap-2 text-blue-600 dark:text-blue-400">
              <span className="animate-pulse">جاري الاتصال بالسيرفر جاري جلب البيانات... ⏳</span>
            </div>
          )}

          {error && (
            <div className="text-red-500 font-medium">
              ❌ تعذر الاتصال بالخادم: {error}
            </div>
          )}

          {!loading && !error && (
            <div>
              <div className="inline-block bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-400 px-4 py-1 rounded-full text-sm font-semibold mb-4">
                تم الاتصال بالـ API بنجاح! ✅
              </div>
              
              <div className="text-right">
                <p className="text-xs text-zinc-500 mb-2 font-mono">البيانات القادمة من السيرفر:</p>
                <pre className="bg-zinc-800 text-green-400 p-4 rounded-lg text-xs overflow-x-auto dir-ltr">
                  {JSON.stringify(data, null, 2)}
                </pre>
              </div>
            </div>
          )}
        </div>

        {/* أزرار سريعة للانتقال */}
        <div className="flex flex-wrap gap-4 mt-8 w-full justify-center">
          <a
            href="/login"
            className="flex h-11 items-center justify-center px-6 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700 transition"
          >
            صفحة تسجيل الدخول
          </a>
          <a
            href="https://bookingsystem-krms.onrender.com/api"
            target="_blank"
            rel="noopener noreferrer"
            className="flex h-11 items-center justify-center px-6 rounded-xl border border-zinc-300 dark:border-zinc-600 text-zinc-700 dark:text-zinc-200 font-medium hover:bg-zinc-100 dark:hover:bg-zinc-700 transition"
          >
            رابط الـ API المباشر 🔗
          </a>
        </div>

      </main>
    </div>
  );
}
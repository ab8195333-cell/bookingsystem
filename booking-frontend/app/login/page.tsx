'use client';

import { useState, FormEvent } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function LoginPage() {
  const [formData, setFormData] = useState({
    email: '',
    password: '',
  });
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const router = useRouter();

  const handleSubmit = async (e: FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    setLoading(true);
    setError(null);

    const apiUrl = 'https://bookingsystem-krms.onrender.com/api';

    try {
      const response = await fetch(`${apiUrl}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          email: formData.email,
          password: formData.password,
        }),
      });

      const contentType = response.headers.get('content-type');
      if (!contentType || !contentType.includes('application/json')) {
        throw new Error('تعذر الاتصال بالخادم. الاستجابة ليست بتنسيق JSON.');
      }

      const data = await response.json();

      if (!response.ok) {
        if (data.errors) {
          const errorMessages = Object.values(data.errors).flat().join(' | ');
          throw new Error(errorMessages);
        }
        throw new Error(data.message || 'البريد الإلكتروني أو كلمة المرور غير صحيحة');
      }

      const token = data.token || data.access_token || data.data?.token;
      if (token) {
        localStorage.setItem('token', token);
      }
      
      const user = data.user || data.data?.user;
      if (user) {
        localStorage.setItem('user', JSON.stringify(user));
      }

      router.push('/bookings');
    } catch (err: unknown) {
      console.error('Login error:', err);
      if (err instanceof Error) {
        setError(err.message);
      } else {
        setError('حدث خطأ غير متوقع أثناء تسجيل الدخول');
      }
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-slate-100 p-6 flex items-center justify-center font-sans" dir="rtl">
      <div className="max-w-md w-full bg-white p-8 rounded-3xl shadow-xl border border-slate-200">
        
        <div className="text-center mb-8">
          <h1 className="text-3xl font-black text-slate-800 mb-2">مرحباً بك مجدداً 👋</h1>
          <p className="text-sm font-medium text-slate-500">سجل الدخول لإدارة نظام الحجوزات الخاص بك</p>
        </div>

        {error && (
          <div className="bg-red-50 border border-red-200 text-red-600 p-4 rounded-xl text-sm mb-6 font-bold leading-relaxed">
            ❌ {error}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-5">
          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">البريد الإلكتروني</label>
            <input
              type="email"
              required
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="name@example.com"
            />
          </div>

          <div>
            <label className="block text-sm font-bold text-slate-700 mb-2">كلمة المرور</label>
            <input
              type="password"
              required
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              className="w-full bg-slate-50 border border-slate-300 rounded-xl p-3.5 text-slate-900 font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white transition"
              placeholder="••••••••"
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition shadow-lg shadow-blue-600/20 disabled:opacity-50 mt-2"
          >
            {loading ? 'جاري تسجيل الدخول...' : 'تسجيل الدخول 🚀'}
          </button>
        </form>

        <div className="mt-6 text-center border-t border-slate-100 pt-5 text-sm text-slate-600">
          <span>ليس لديك حساب؟ </span>
          <Link href="/register" className="font-bold text-blue-600 hover:underline">
            إنشاء حساب جديد
          </Link>
        </div>

      </div>
    </div>
  );
}
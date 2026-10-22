'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function LoginPage() {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const router = useRouter();

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';

    try {
      const res = await fetch(`${apiUrl}/login`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({ email, password }),
      });

      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.message || 'بيانات الدخول غير صحيحة.');
      }

      // حفظ الرمز والانتقال للصفحة الرئيسية
      localStorage.setItem('token', data.token);
      router.push('/');
    } catch (err: any) {
      setError(err.message || 'حدث خطأ أثناء تسجيل الدخول.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" dir="rtl">
      <div className="max-w-md w-full">
        <div className="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
          
          {/* Header */}
          <div className="bg-blue-600 py-6 text-center">
            <h4 className="text-white font-bold text-xl mb-0">مرحباً بك في BOOKINGSAAS</h4>
          </div>

          {/* Body */}
          <div className="p-8 bg-white">
            <h5 className="text-center font-bold mb-6 text-gray-600">تسجيل الدخول</h5>

            {error && (
              <div className="mb-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm text-center font-medium border border-red-100">
                {error}
              </div>
            )}

            <form onSubmit={handleLogin} className="space-y-4">
              
              {/* Email */}
              <div>
                <label className="block text-xs font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
                <div className="relative flex items-center">
                  <span className="absolute right-3 text-gray-400">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                  </span>
                  <input
                    type="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="w-full pr-10 pl-4 py-2.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm text-gray-800"
                    placeholder="name@example.com"
                  />
                </div>
              </div>

              {/* Password */}
              <div>
                <label className="block text-xs font-bold text-gray-700 mb-1">كلمة المرور</label>
                <div className="relative flex items-center">
                  <span className="absolute right-3 text-gray-400">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                  </span>
                  <input
                    type="password"
                    required
                    value={password}
                    onChange={(e) => setPassword(e.target.value)}
                    className="w-full pr-10 pl-4 py-2.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm text-gray-800"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              {/* Submit Button */}
              <div className="pt-2">
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2 disabled:opacity-50"
                >
                  {loading ? 'جاري تسجيل الدخول...' : (
                    <>
                      <span>دخول</span>
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                    </>
                  )}
                </button>
              </div>

              {/* Register Link */}
              <div className="text-center pt-4 border-t border-gray-100 mt-6">
                <p className="text-sm text-gray-500 mb-2">ليس لديك حساب حتى الآن؟</p>
                <Link
                  href="/register"
                  className="w-full py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition duration-200 flex items-center justify-center gap-2 text-sm"
                >
                  <svg className="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                  <span>إنشاء حساب جديد (Register)</span>
                </Link>
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>
  );
}
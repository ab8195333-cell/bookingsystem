'use client';

import { useState } from 'react';
import { useRouter } from 'next/navigation';
import Link from 'next/link';

export default function RegisterPage() {
  const [name, setName] = useState('');
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const [passwordConfirmation, setPasswordConfirmation] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const router = useRouter();

  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    const apiUrl = process.env.NEXT_PUBLIC_API_URL || 'https://bookingsystem-krms.onrender.com/api';

    try {
      const res = await fetch(`${apiUrl}/register`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: JSON.stringify({
          name,
          email,
          password,
          password_confirmation: passwordConfirmation,
        }),
      });

      const data = await res.json();

      if (!res.ok) {
        throw new Error(data.message || 'فشل إنشاء الحساب، يرجى التأكد من البيانات المدخلة.');
      }

      // 1. حفظ توكن المصادقة في الـ localStorage
      if (data.token) {
        localStorage.setItem('token', data.token);
      }

      // 2. التوجيه المباشر إلى صفحة خيارات الاشتراك (التجربة المجانية أو 5$)
      router.push('/subscription');

    } catch (err: any) {
      setError(err.message || 'حدث خطأ غير متوقع أثناء إنشاء الحساب.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gray-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" dir="rtl">
      <div className="max-w-md w-full">
        <div className="bg-white shadow-2xl rounded-3xl overflow-hidden border border-gray-100">
          
          {/* ترويسة النموذج */}
          <div className="bg-blue-600 py-6 text-center">
            <h4 className="text-white font-bold text-xl mb-0">مرحباً بك في BOOKINGSAAS</h4>
            <p className="text-blue-100 text-xs mt-1">إنشاء حساب جديد للاستفادة من كافة الخدمات</p>
          </div>

          {/* محتوى النموذج */}
          <div className="p-8 bg-white">
            <h5 className="text-center font-bold mb-6 text-gray-600">إنشاء حساب جديد</h5>

            {/* عرض الأخطاء إن وجدت */}
            {error && (
              <div className="mb-4 p-3 bg-red-50 text-red-600 rounded-xl text-sm text-center font-medium border border-red-100">
                {error}
              </div>
            )}

            <form onSubmit={handleRegister} className="space-y-4">
              
              {/* حقل الاسم الكامل */}
              <div>
                <label className="block text-xs font-bold text-gray-700 mb-1">الاسم الكامل</label>
                <div className="relative flex items-center">
                  <span className="absolute right-3 text-gray-400">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </span>
                  <input
                    type="text"
                    required
                    value={name}
                    onChange={(e) => setName(e.target.value)}
                    className="w-full pr-10 pl-4 py-2.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm text-gray-800"
                    placeholder="أدخل اسمك الكامل"
                  />
                </div>
              </div>

              {/* حقل البريد الإلكتروني */}
              <div>
                <label className="block text-xs font-bold text-gray-700 mb-1">البريد الإلكتروني</label>
                <div className="relative flex items-center">
                  <span className="absolute right-3 text-gray-400">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                    </svg>
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

              {/* حقل كلمة المرور */}
              <div>
                <label className="block text-xs font-bold text-gray-700 mb-1">كلمة المرور</label>
                <div className="relative flex items-center">
                  <span className="absolute right-3 text-gray-400">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
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

              {/* حقل تأكيد كلمة المرور */}
              <div>
                <label className="block text-xs font-bold text-gray-700 mb-1">تأكيد كلمة المرور</label>
                <div className="relative flex items-center">
                  <span className="absolute right-3 text-gray-400">
                    <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                  </span>
                  <input
                    type="password"
                    required
                    value={passwordConfirmation}
                    onChange={(e) => setPasswordConfirmation(e.target.value)}
                    className="w-full pr-10 pl-4 py-2.5 bg-gray-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 text-sm text-gray-800"
                    placeholder="••••••••"
                  />
                </div>
              </div>

              {/* زر إرسال النموذج */}
              <div className="pt-2">
                <button
                  type="submit"
                  disabled={loading}
                  className="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-md transition duration-200 flex items-center justify-center gap-2 disabled:opacity-50"
                >
                  {loading ? 'جاري إنشاء الحساب...' : (
                    <>
                      <span>تسجيل حساب جديد</span>
                      <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                      </svg>
                    </>
                  )}
                </button>
              </div>

              {/* العودة لصفحة تسجيل الدخول */}
              <div className="text-center pt-4 border-t border-gray-100 mt-6">
                <p className="text-sm text-gray-500 mb-2">لديك حساب بالفعل؟</p>
                <Link
                  href="/login"
                  className="w-full py-2.5 border border-gray-300 hover:bg-gray-50 text-gray-700 font-bold rounded-xl transition duration-200 flex items-center justify-center gap-2 text-sm"
                >
                  <span>تسجيل الدخول</span>
                </Link>
              </div>

            </form>
          </div>

        </div>
      </div>
    </div>
  );
}
/** @type {import('next').NextConfig} */
const nextConfig = {
  // تفعيل وضع الـ Strict Mode لفحص الأخطاء بدقة أثناء التطوير
  reactStrictMode: true,
  
  // إذا كنت تريد ربط الـ API بالخارج مباشرة عبر المتغيرات البيئية
  env: {
    NEXT_PUBLIC_API_URL: process.env.NEXT_PUBLIC_API_URL || 'http://127.0.0.1:8000/api',
  },
};

module.exports = nextConfig;
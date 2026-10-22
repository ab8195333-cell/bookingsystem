import axios from 'axios';

// إنشاء نسخة مخصصة من أكسيوس مرتبطة برابط الباك اند على Render
const API = axios.create({
  baseURL: 'https://bookingsystem-krms.onrender.com/api',
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

// إضافة التوكن تلقائياً مع كل طلب يتم إرساله للـ Backend
API.interceptors.request.use(
  (config) => {
    // التأكد من أن الكود يعمل في متصفح المستخدم وليس في الـ Server-side
    if (typeof window !== 'undefined') {
      const token = localStorage.getItem('token');
      if (token) {
        config.headers.Authorization = `Bearer ${token}`;
      }
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

export default API;
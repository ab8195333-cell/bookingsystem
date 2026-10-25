// 1. تعريف TextEncoder و TextDecoder لحل المشكلة فوراً
const { TextEncoder, TextDecoder } = require('util');
global.TextEncoder = TextEncoder;
global.TextDecoder = TextDecoder;

const { render, screen, waitFor } = require('@testing-library/react');
require('@testing-library/jest-dom');

// استيراد صفحة الحجوزات
const BookingsPage = require('../app/bookings/page').default;

// 2. محاكاة useRouter لـ Next.js
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
}));

// 3. محاكاة fetch لمنع أخطاء الاتصال بالسيرفر أثناء الاختبار
global.fetch = jest.fn(() =>
  Promise.resolve({
    ok: true,
    headers: {
      get: () => 'application/json',
    },
    json: () => Promise.resolve({ data: { bookings: [], bookingsCount: 0, totalAmount: 0 } }),
  })
);

describe('BookingsPage Integration Tests', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders bookings page successfully without crashing', async () => {
    render(<BookingsPage />);

    // التأكد من ظهور الترويسة الأساسية للصفحة
    const headerElement = screen.getByText(/نظام الحجوزات الذكي/i);
    expect(headerElement).toBeInTheDocument();

    // الانتظار لحين انتهاء التحميل وتحديث الحالة
    await waitFor(() => {
      expect(screen.queryByText(/جاري جلب بيانات الحجوزات/i)).not.toBeInTheDocument();
    });
  });
});
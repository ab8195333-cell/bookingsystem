const { TextEncoder, TextDecoder } = require('util');
global.TextEncoder = TextEncoder;
global.TextDecoder = TextDecoder;

const { render, screen } = require('@testing-library/react');
require('@testing-library/jest-dom');

// استيراد صفحة التعديل
const EditBookingPage = require('../app/bookings/edit/[id]/page').default;

// 1. محاكاة useRouter و useParams لـ Next.js App Router
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
  useParams() {
    return { id: '1' };
  },
}));

// محاكاة React.use لفك الـ Promise الخاصة بـ params في الاختبارات
jest.mock('react', () => ({
  ...jest.requireActual('react'),
  use: (promise) => {
    if (promise && typeof promise.then === 'function') {
      return { id: '1' };
    }
    return promise;
  },
}));

// 2. محاكاة الـ fetch لجلب بيانات الحجز القديمة
global.fetch = jest.fn(() =>
  Promise.resolve({
    ok: true,
    json: () =>
      Promise.resolve({
        data: {
          customer_name: 'محمد عبدالله',
          service_name: 'تطوير واجهات',
          amount: 500,
          booking_date: '2026-06-01',
          status: 'confirmed',
        },
      }),
  })
);

describe('EditBookingPage Integration Tests', () => {
  beforeEach(() => {
    jest.clearAllMocks();
  });

  it('renders loading state or edit booking header successfully', async () => {
    render(<EditBookingPage params={Promise.resolve({ id: '1' })} />);

    // التحقق من ظهور نص التحميل أو عنوان الصفحة الأساسي
    const headerElement = screen.getByText(/جاري تحميل بيانات الحجز|تعديل الحجز/i);
    expect(headerElement).toBeInTheDocument();
  });
});
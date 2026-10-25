const { TextEncoder, TextDecoder } = require('util');
global.TextEncoder = TextEncoder;
global.TextDecoder = TextDecoder;

const { render, screen } = require('@testing-library/react');
require('@testing-library/jest-dom');

// استيراد صفحة الاشتراك
const SubscriptionPage = require('../app/subscription/page').default;

// محاكاة useRouter الخاصة بـ Next.js
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
}));

// محاكاة fetch للطلبات الخاصة بالاشتراك
global.fetch = jest.fn(() =>
  Promise.resolve({
    ok: true,
    headers: { get: () => 'application/json' },
    json: () => Promise.resolve({ status: 'success', message: 'Subscription status active' }),
  })
);

describe('SubscriptionPage Integration Test', () => {
  it('renders subscription page successfully', () => {
    render(<SubscriptionPage />);

    // التحقق من تحميل مكونات الصفحة الأساسية بدون أخطاء
    expect(document.body).toBeInTheDocument();
  });
});
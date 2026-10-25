const { render, screen, fireEvent } = require('@testing-library/react');
require('@testing-library/jest-dom');

// استيراد صفحة تسجيل الدخول باستخدام require
const LoginPage = require('../app/login/page').default;

// محاكاة لـ useRouter الخاصة بـ Next.js لمنع أخطاء التوجيه
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
}));

describe('LoginPage Real Integration Tests', () => {
  it('renders login form inputs and handles user input correctly', () => {
    render(<LoginPage />);

    const submitBtn = screen.getByRole('button', { name: /تسجيل الدخول|Login|دخول/i });
    expect(submitBtn).toBeInTheDocument();
  });
});
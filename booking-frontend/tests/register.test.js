const { render, screen } = require('@testing-library/react');
require('@testing-library/jest-dom');

// استيراد صفحة التسجيل باستخدام require
const RegisterPage = require('../app/register/page').default;

// محاكاة لـ useRouter الخاصة بـ Next.js
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
}));

describe('RegisterPage Real Integration Tests', () => {
  it('renders registration form inputs correctly', () => {
    render(<RegisterPage />);

    const submitBtn = screen.getByRole('button', { name: /إنشاء حساب|Register|تسجيل/i });
    expect(submitBtn).toBeInTheDocument();
  });
});
const { render, screen } = require('@testing-library/react');
require('@testing-library/jest-dom');

// 🔴 التعديل هنا: استيراد صفحة لوحة التحكم الحقيقية بالمسار الصحيح من داخل مجلد app
const DashboardPage = require('../app/dashboard/page').default;

// محاكاة لـ useRouter الخاصة بـ Next.js لمنع أخطاء التوجيه
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
}));

describe('DashboardPage Real Integration Tests', () => {
  it('renders dashboard layout and core elements successfully', () => {
    render(<DashboardPage />);

    const dashboardElement = screen.getByText(/لوحة التحكم|Dashboard|Welcome/i);
    expect(dashboardElement).toBeInTheDocument();
  });
});
const { render, screen } = require('@testing-library/react');
require('@testing-library/jest-dom');

// مكون محاكاة صفحة محمية تتطلب تسجيل الدخول
function ProtectedDashboard({ isAuthenticated }) {
  if (!isAuthenticated) {
    return <div role="alert">يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة</div>;
  }

  return (
    <div>
      <h1>لوحة التحكم والإدارة</h1>
      <p>أهلاً بك في نظام الحجوزات المحمي</p>
    </div>
  );
}

describe('Authentication & Route Guard Tests', () => {
  it('shows login warning and blocks access when user is not authenticated', () => {
    render(<ProtectedDashboard isAuthenticated={false} />);

    // التحقق من ظهور رسالة المنع والتنبيه للمستخدم غير المسجل
    const alertMessage = screen.getByRole('alert');
    expect(alertMessage).toHaveTextContent('يرجى تسجيل الدخول أولاً للوصول إلى هذه الصفحة');
    
    // التأكد من عدم ظهور محتوى لوحة التحكم السري
    expect(screen.queryByText('لوحة التحكم والإدارة')).not.toBeInTheDocument();
  });

  it('renders dashboard content successfully when user is authenticated', () => {
    render(<ProtectedDashboard isAuthenticated={true} />);

    // التأكد من ظهور محتوى لوحة التحكم للمستخدم المصرح له
    expect(screen.getByText('لوحة التحكم والإدارة')).toBeInTheDocument();
    expect(screen.queryByRole('alert')).not.toBeInTheDocument();
  });
});
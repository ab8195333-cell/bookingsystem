const { render, screen, fireEvent } = require('@testing-library/react');
require('@testing-library/jest-dom');

// مكون نموذج تسجيل الدخول مع محاكاة التحقق من الصحة (Validation)
function ValidatedLoginForm({ onSubmit }) {
  const [error, setError] = require('react').useState('');

  const handleSubmit = (e) => {
    e.preventDefault();
    const email = e.target.elements.email.value;
    const password = e.target.elements.password.value;

    if (!email || !password) {
      setError('جميع الحقول مطلوبة');
      return;
    }
    if (!email.includes('@')) {
      setError('البريد الإلكتروني غير صالح');
      return;
    }
    setError('');
    onSubmit();
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>تسجيل الدخول</h2>
      {error && <div role="alert">{error}</div>}
      
      <label htmlFor="email">البريد الإلكتروني</label>
      <input id="email" name="email" type="text" />
      
      <label htmlFor="password">كلمة المرور</label>
      <input id="password" name="password" type="password" />
      
      <button type="submit">دخول</button>
    </form>
  );
}

describe('Form Validation Component Tests', () => {
  it('shows error message when submitting empty fields', () => {
    const handleSubmit = jest.fn();
    render(<ValidatedLoginForm onSubmit={handleSubmit} />);

    const submitBtn = screen.getByRole('button', { name: /دخول/i });
    fireEvent.click(submitBtn);

    // التحقق من ظهور رسالة الخطأ الخاصة بالحقول الفارغة
    expect(screen.getByRole('alert')).toHaveTextContent('جميع الحقول مطلوبة');
    expect(handleSubmit).not.toHaveBeenCalled();
  });

  it('shows error message when email format is invalid', () => {
    const handleSubmit = jest.fn();
    render(<ValidatedLoginForm onSubmit={handleSubmit} />);

    const emailInput = screen.getByLabelText(/البريد الإلكتروني/i);
    const passwordInput = screen.getByLabelText(/كلمة المرور/i);
    const submitBtn = screen.getByRole('button', { name: /دخول/i });

    // إدخال بريد خاطئ بدون علامة @
    fireEvent.change(emailInput, { target: { value: 'invalidemail.com' } });
    fireEvent.change(passwordInput, { target: { value: 'password123' } });
    fireEvent.click(submitBtn);

    // التحقق من ظهور رسالة خطأ البريد الإلكتروني
    expect(screen.getByRole('alert')).toHaveTextContent('البريد الإلكتروني غير صالح');
    expect(handleSubmit).not.toHaveBeenCalled();
  });

  it('submits successfully when valid data is provided', () => {
    const handleSubmit = jest.fn();
    render(<ValidatedLoginForm onSubmit={handleSubmit} />);

    const emailInput = screen.getByLabelText(/البريد الإلكتروني/i);
    const passwordInput = screen.getByLabelText(/كلمة المرور/i);
    const submitBtn = screen.getByRole('button', { name: /دخول/i });

    // إدخال بيانات صحيحة
    fireEvent.change(emailInput, { target: { value: 'user@example.com' } });
    fireEvent.change(passwordInput, { target: { value: 'password123' } });
    fireEvent.click(submitBtn);

    // التأكد من عدم وجود أخطاء واستدعاء دالة الإرسال بنجاح
    expect(screen.queryByRole('alert')).not.toBeInTheDocument();
    expect(handleSubmit).toHaveBeenCalledTimes(1);
  });
});
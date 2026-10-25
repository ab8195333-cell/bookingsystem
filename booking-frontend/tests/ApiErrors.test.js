const { render, screen, fireEvent, waitFor } = require('@testing-library/react');
require('@testing-library/jest-dom');

// مكون يحاكي جلب البيانات أو إرسال طلب مع التعامل مع خطأ السيرفر
function BookingSubmissionWithApi({ apiMock }) {
  const [error, setError] = require('react').useState(null);
  const [success, setSuccess] = require('react').useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError(null);
    try {
      await apiMock();
      setSuccess(true);
    } catch (err) {
      setError(err.message || 'حدث خطأ غير متوقع');
    }
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>تأكيد الحجز</h2>
      {error && <div role="alert" style={{ color: 'red' }}>{error}</div>}
      {success && <div role="status">تم الحجز بنجاح</div>}
      
      <button type="submit">إرسال الطلب</button>
    </form>
  );
}

describe('API Error Handling Component Tests', () => {
  it('displays error message when API request fails with 401 Unauthorized', async () => {
    // محاكاة دالة API تفشل وترجع خطأ
    const failingApi = jest.fn().mockRejectedValue(new Error('بيانات الاعتماد غير صحيحة (401)'));

    render(<BookingSubmissionWithApi apiMock={failingApi} />);

    const submitBtn = screen.getByRole('button', { name: /إرسال الطلب/i });
    fireEvent.click(submitBtn);

    // الانتظار حتى ظهور رسالة الخطأ القادمة من السيرفر
    const errorMessage = await screen.findByRole('alert');
    expect(errorMessage).toHaveTextContent('بيانات الاعتماد غير صحيحة (401)');
  });

  it('displays success message when API request succeeds', async () => {
    // محاكاة دالة API تنجح بدون أخطاء
    const successApi = jest.fn().mockResolvedValue({ status: 200 });

    render(<BookingSubmissionWithApi apiMock={successApi} />);

    const submitBtn = screen.getByRole('button', { name: /إرسال الطلب/i });
    fireEvent.click(submitBtn);

    // الانتظار حتى ظهور رسالة النجاح
    const successMessage = await screen.findByRole('status');
    expect(successMessage).toHaveTextContent('تم الحجز بنجاح');
  });
});
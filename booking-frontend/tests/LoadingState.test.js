const { render, screen, fireEvent, waitFor } = require('@testing-library/react');
require('@testing-library/jest-dom');

// مكون يحاكي حالة التحميل وتعطيل الأزرار أثناء الإرسال
function BookingLoadingForm({ onSubmit }) {
  const [loading, setLoading] = require('react').useState(false);

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    await onSubmit();
    setLoading(false);
  };

  return (
    <form onSubmit={handleSubmit}>
      <h2>حجز موعد</h2>
      <button type="submit" disabled={loading}>
        {loading ? 'جاري الإرسال...' : 'تأكيد الحجز'}
      </button>
    </form>
  );
}

describe('Loading State Component Tests', () => {
  it('disables submit button and shows loading text during submission', async () => {
    // دالة محاكاة تأخير بسيط للشبكة (Promise مع تأخير)
    const delayedApi = () => new Promise((resolve) => setTimeout(resolve, 100));

    render(<BookingLoadingForm onSubmit={delayedApi} />);

    const submitBtn = screen.getByRole('button', { name: /تأكيد الحجز/i });

    // التأكد من أن الزر مفعل في البداية
    expect(submitBtn).not.toBeDisabled();

    // الضغط على الزر لمحاكاة الإرسال
    fireEvent.click(submitBtn);

    // التحقق فوراً من أن الزر أصبح معطلاً وأن النص تغير إلى جاري الإرسال
    expect(screen.getByRole('button', { name: /جاري الإرسال.../i })).toBeDisabled();

    // الانتظار حتى تنتهي العملية ويعود الزر لحالته الطبيعية
    await waitFor(() => {
      expect(screen.getByRole('button', { name: /تأكيد الحجز/i })).not.toBeDisabled();
    });
  });
});
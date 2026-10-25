const { render, screen, fireEvent } = require('@testing-library/react');
require('@testing-library/jest-dom');

// مكون تجريبي لنموذج الحجز (أو استبدله بالمكون الحقيقي لديك لاحقاً)
function BookingCreateForm({ onBook }) {
  return (
    <form onSubmit={(e) => { e.preventDefault(); onBook(); }}>
      <h2>إنشاء حجز جديد</h2>
      <label htmlFor="service">اختر الخدمة</label>
      <input id="service" type="text" placeholder="enter service" />
      
      <label htmlFor="date">تاريخ الحجز</label>
      <input id="date" type="date" />
      
      <button type="submit">تأكيد الحجز</button>
    </form>
  );
}

describe('Booking Create Component Tests', () => {
  it('renders booking form inputs and submit button', () => {
    render(<BookingCreateForm onBook={() => {}} />);

    // التحقق من ظهور حقول وأزرار إنشاء الحجز
    expect(screen.getByPlaceholderText('enter service')).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /تأكيد الحجز/i })).toBeInTheDocument();
  });

  it('allows user to fill booking form and submit successfully', () => {
    const handleBook = jest.fn();
    render(<BookingCreateForm onBook={handleBook} />);

    const serviceInput = screen.getByPlaceholderText('enter service');
    const submitBtn = screen.getByRole('button', { name: /تأكيد الحجز/i });

    // محاكاة إدخال بيانات الخدمة والضغط على زر التأكيد
    fireEvent.change(serviceInput, { target: { value: 'استشارة برمجية' } });
    fireEvent.click(submitBtn);

    // التأكد من استدعاء دالة الحجز
    expect(handleBook).toHaveBeenCalledTimes(1);
  });
});
const { render, screen, fireEvent } = require('@testing-library/react');
require('@testing-library/jest-dom');

// مكون تجريبي لقائمة الحجوزات
function BookingList({ bookings = [], onDelete }) {
  if (bookings.length === 0) {
    return <div>لا توجد حجوزات حالياً</div>;
  }

  return (
    <div>
      <h2>قائمة الحجوزات</h2>
      <ul>
        {bookings.map((booking) => (
          <li key={booking.id}>
            <span>{booking.service} - {booking.date}</span>
            <button onClick={() => onDelete(booking.id)}>إلغاء الحجز</button>
          </li>
        ))}
      </ul>
    </div>
  );
}

describe('Booking List Component Tests', () => {
  it('renders empty message when there are no bookings', () => {
    render(<BookingList bookings={[]} onDelete={() => {}} />);

    // التحقق من ظهور رسالة لا توجد حجوزات
    expect(screen.getByText('لا توجد حجوزات حالياً')).toBeInTheDocument();
  });

  it('renders a list of bookings correctly', () => {
    const mockBookings = [
      { id: 1, service: 'استشارة برمجية', date: '2026-11-01' },
      { id: 2, service: 'صيانة سيرفر', date: '2026-11-05' }
    ];

    render(<BookingList bookings={mockBookings} onDelete={() => {}} />);

    // التحقق من ظهور نصوص الحجوزات
    expect(screen.getByText(/استشارة برمجية/i)).toBeInTheDocument();
    expect(screen.getByText(/صيانة سيرفر/i)).toBeInTheDocument();
  });

  it('calls onDelete function when clicking cancel button', () => {
    const mockBookings = [
      { id: 1, service: 'استشارة برمجية', date: '2026-11-01' }
    ];
    const handleDelete = jest.fn();

    render(<BookingList bookings={mockBookings} onDelete={handleDelete} />);

    const deleteBtn = screen.getByRole('button', { name: /إلغاء الحجز/i });
    fireEvent.click(deleteBtn);

    // التأكد من استدعاء دالة الحذف وتمرير الرقم المعرف (ID) الصحيح
    expect(handleDelete).toHaveBeenCalledTimes(1);
    expect(handleDelete).toHaveBeenCalledWith(1);
  });
});
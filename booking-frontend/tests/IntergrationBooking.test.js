const { render, screen, waitFor } = require('@testing-library/react');
require('@testing-library/jest-dom');

// محاكاة جلب بيانات الحجوزات من السيرفر الحقيقي
function RealBookingApp({ fetchBookings }) {
  const [bookings, setBookings] = require('react').useState([]);
  const [loading, setLoading] = require('react').useState(true);

  require('react').useEffect(() => {
    fetchBookings()
      .then((data) => {
        setBookings(data);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [fetchBookings]);

  if (loading) return <div>جاري تحميل الحجوزات...</div>;

  if (bookings.length === 0) return <div>لا توجد حجوزات مسجلة</div>;

  return (
    <ul>
      {bookings.map((b) => (
        <li key={b.id}>{b.title}</li>
      ))}
    </ul>
  );
}

describe('Integration & Real Components Test', () => {
  it('fetches and displays bookings successfully from API', async () => {
    // محاكاة استجابة ناجحة من السيرفر
    const mockApiCall = jest.fn().mockResolvedValue([
      { id: 1, title: 'حجز موعد صيانة' },
      { id: 2, title: 'حجز استشارة تقنية' }
    ]);

    render(<RealBookingApp fetchBookings={mockApiCall} />);

    // التحقق من ظهور حالة التحميل في البداية
    expect(screen.getByText('جاري تحميل الحجوزات...')).toBeInTheDocument();

    // الانتظار حتى انتهاء جلب البيانات وعرض الحجوزات على الشاشة
    const bookingItem = await screen.findByText('حجز موعد صيانة');
    expect(bookingItem).toBeInTheDocument();
    expect(screen.getByText('حجز استشارة تقنية')).toBeInTheDocument();
  });
});
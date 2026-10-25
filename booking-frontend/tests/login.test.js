const { render, screen } = require('@testing-library/react');
require('@testing-library/jest-dom');

function BookingHeader() {
  return <h1>نظام الحجوزات</h1>;
}

describe('Booking Component', () => {
  it('renders the booking heading correctly', () => {
    render(<BookingHeader />);
    const heading = screen.getByText('نظام الحجوزات');
    expect(heading).toBeInTheDocument();
  });
});
import { render, screen } from '@testing-library/react'
import '@testing-library/jest-dom'

function BookingHeader() {
  return <h1>نظام الحجوزات</h1>
}

describe('Booking Component', () => {
  it('renders the booking heading correctly', () => {
    render(<BookingHeader />)
    // نص غير موجود مطلقاً ليتسبب في فشل الاختبار
    const heading = screen.getByText('نص غير موجود سينشئ خطأ')
    expect(heading).toBeInTheDocument()
  })
})
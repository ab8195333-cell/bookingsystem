export interface Booking {
  id: number;
  user_id: number;
  customer_name: string;
  service_name: string;
  amount: number;
  booking_date: string;
  appointment_date: string;
  created_at?: string;
  updated_at?: string;
}

export interface BookingsApiResponse {
  status: string;
  data: {
    bookings: Booking[];
    bookingsCount: number;
    totalAmount: number;
  };
}

export interface User {
  id: number;
  name: string;
  email: string;
  is_subscribed: boolean;
  created_at: string;
}
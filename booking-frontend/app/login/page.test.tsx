import { render, screen, fireEvent } from '@testing-library/react';
import LoginPage from './page';

// Mock Router
jest.mock('next/navigation', () => ({
  useRouter() {
    return {
      push: jest.fn(),
    };
  },
}));

describe('LoginPage Component', () => {
  it('يعرض حقول تسجيل الدخول بشكل صحيح', () => {
    render(<LoginPage />);

    expect(screen.getByPlaceholderText('example@domain.com')).toBeInTheDocument();
    expect(screen.getByPlaceholderText('••••••••')).toBeInTheDocument();
    expect(screen.getByRole('button', { name: /دخول/i })).toBeInTheDocument();
  });

  it('يسمح للمستخدم بإدخال البيانات', () => {
    render(<LoginPage />);

    const emailInput = screen.getByPlaceholderText('example@domain.com') as HTMLInputElement;
    const passwordInput = screen.getByPlaceholderText('••••••••') as HTMLInputElement;

    fireEvent.change(emailInput, { target: { value: 'test@example.com' } });
    fireEvent.change(passwordInput, { target: { value: 'password123' } });

    expect(emailInput.value).toBe('test@example.com');
    expect(passwordInput.value).toBe('password123');
  });
});
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisteredUserController extends Controller
{
    // هذه الدالة هي التي كانت مفقودة في صورتك الأخيرة
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // تفعيل ميزة الـ 5 أيام تجربة مجانية
            'trial_ends_at' => now()->addDays(5), 
            'is_paid' => false,
        ]);

        Auth::login($user);

        return redirect()->route('bookings.index');
    }
}
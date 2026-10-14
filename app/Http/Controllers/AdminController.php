<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // يمكنك إرجاع عرض (View) لوحة التحكم هنا
        return view('admin.dashboard');
    }
}
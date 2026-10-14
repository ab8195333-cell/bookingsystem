<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * الحقول القابلة للتعبئة (Mass Assignment).
     * تم إضافة 'username' هنا لحل خطأ General error: 1364.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'username', 
    ];

    /**
     * الحقول التي يجب إخفاؤها عند تحويل الموديل إلى مصفوفة أو JSON.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * تحويل أنواع البيانات.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
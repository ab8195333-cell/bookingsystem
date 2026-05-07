<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['name', 'slug', 'user_id', 'is_active'];

    public function owner() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class);
    }
}
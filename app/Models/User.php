<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = ['name', 'email', 'password', 'store_id'];

    public function store() {
        return $this->belongsTo(Store::class);
    }

    // هل المستخدم يمتلك متجر نشط؟
    public function hasActiveStore() {
        return $this->store && $this->store->is_active;
    }
}
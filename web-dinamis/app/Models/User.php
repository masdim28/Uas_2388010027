<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Cart;
use App\Models\Order;
use App\Models\CartItem; // Pastikan ini juga di-import jika dipakai

class User extends Authenticatable implements \Illuminate\Contracts\Auth\MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',    
        'address',  
        'gender',   
        'is_blocked',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relasi ke Cart
    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    // Relasi ke Order
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Mengambil item keranjang melalui Cart
    public function cartItems()
    {
        return $this->hasManyThrough(CartItem::class, Cart::class);
    }
}
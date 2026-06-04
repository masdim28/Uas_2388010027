<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'account_number',
        'account_name',
        'qr_code',
        'is_active',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}

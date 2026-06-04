<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     * Sesuai dengan rencana migration: product_id, color, size, dan stock.
     */
    protected $fillable = [
        'product_id',
        'color',
        'size',
        'stock',
        'price',
        'weight',
        'status',
        'image',
    ];

    /**
     * Relasi ke Model Product.
     * Satu varian hanya dimiliki oleh satu produk.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
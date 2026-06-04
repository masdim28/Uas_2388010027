<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * Kolom yang dapat diisi secara massal.
     * image_path akan menyimpan lokasi file gambar di storage.
     */
    protected $fillable = [
        'product_id',
        'image_path',
    ];

    /**
     * Relasi ke model Product (Inverse dari HasMany).
     * Satu gambar ini dimiliki oleh satu produk.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
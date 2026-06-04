<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    use HasFactory;

    // Pastikan 'parent_id' ditambahkan agar bisa diisi saat seeding atau input form
    protected $fillable = ['name', 'slug', 'parent_id'];

    /**
     * Relasi: Satu kategori memiliki banyak produk.
     */
    public function products()
{
    return $this->belongsToMany(Product::class, 'category_product');
}
    /**
     * Relasi ke Anak Kategori (Sub-Kategori)
     * Contoh: HIJAB memiliki banyak sub seperti Pashmina, Bergo, dll.
     */
    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Relasi ke Induk Kategori
     * Contoh: Pashmina dimiliki oleh kategori HIJAB.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
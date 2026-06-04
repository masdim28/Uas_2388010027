<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Daftar Kategori Utama dan Sub-Kategorinya
        $categories = [
            'SARIMBIT' => ['Sarimbit Keluarga', 'Sarimbit Couple', 'Sarimbit Lebaran'],
            'BEST SELLER' => [],
            'NEW ARRIVAL' => [],
            'PERLENGKAPAN SALAT' => ['Mukena', 'Sajadah', 'Tasbih', 'Peci', 'Al-Qur’an'],
            'HIJAB' => ['Pashmina', 'Segi Empat', 'Bergo', 'Khimar'],
            'ACCESSORIES' => ['Bros', 'Ciput / Inner', 'Peniti Hijab', 'Bandana / Headpiece'],
        ];

        foreach ($categories as $parentName => $children) {
            // Simpan Kategori Utama
            $parent = Category::create([
                'name' => $parentName,
                'slug' => Str::slug($parentName),
                'parent_id' => null,
            ]);

            // Simpan Sub-Kategori jika ada
            foreach ($children as $childName) {
                Category::create([
                    'name' => $childName,
                    'slug' => Str::slug($childName),
                    'parent_id' => $parent->id, // Menghubungkan ke ID Parent-nya
                ]);
            }
        }
    }
}
<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Memanggil CategorySeeder yang baru saja kita buat
        $this->call([
            CategorySeeder::class,
        ]);

        // User::factory(10)->create();

        // Kamu bisa membiarkan atau menghapus test user ini
        /*
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        */
    }
}
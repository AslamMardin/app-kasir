<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Makanan Ringan', 'slug' => 'makanan-ringan'],
            ['name' => 'Minuman', 'slug' => 'minuman'],
            ['name' => 'Rokok', 'slug' => 'rokok'],
            ['name' => 'Kebutuhan Rumah', 'slug' => 'kebutuhan-rumah'],
            ['name' => 'Perawatan Tubuh', 'slug' => 'perawatan-tubuh'],
            ['name' => 'Obat-obatan', 'slug' => 'obat-obatan'],
            ['name' => 'Alat Tulis', 'slug' => 'alat-tulis'],
            ['name' => 'Susu & Bayi', 'slug' => 'susu-bayi'],
        ];

        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}

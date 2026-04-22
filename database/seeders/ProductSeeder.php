<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // Makanan Ringan
            [
                'barcode' => '8991001100111',
                'name' => 'Chitato Sapi Panggang 68g',
                'category_id' => 1,
                'purchase_price' => 8500,
                'selling_price' => 10500,
                'stock' => 50,
                'unit' => 'pcs',
            ],
            [
                'barcode' => '8991001100222',
                'name' => 'Qtela Singkong Original 60g',
                'category_id' => 1,
                'purchase_price' => 5500,
                'selling_price' => 7000,
                'stock' => 40,
                'unit' => 'pcs',
            ],
            // Minuman
            [
                'barcode' => '8991002200111',
                'name' => 'Aqua 600ml',
                'category_id' => 2,
                'purchase_price' => 2500,
                'selling_price' => 3500,
                'stock' => 100,
                'unit' => 'pcs',
            ],
            [
                'barcode' => '8991002200222',
                'name' => 'Teh Botol Sosro 450ml',
                'category_id' => 2,
                'purchase_price' => 5000,
                'selling_price' => 6500,
                'stock' => 60,
                'unit' => 'pcs',
            ],
            // Rokok
            [
                'barcode' => '8991003300111',
                'name' => 'Sampoerna Mild 16',
                'category_id' => 3,
                'purchase_price' => 28000,
                'selling_price' => 31000,
                'stock' => 20,
                'unit' => 'pcs',
            ],
            // Kebutuhan Rumah
            [
                'barcode' => '8991004400111',
                'name' => 'Rinso Anti Noda 770g',
                'category_id' => 4,
                'purchase_price' => 22000,
                'selling_price' => 25500,
                'stock' => 15,
                'unit' => 'pcs',
            ],
        ];

        foreach ($products as $product) {
            \App\Models\Product::create($product);
        }
    }
}

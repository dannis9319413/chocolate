<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'name'  => '巧纖可可錠 鳳梨益生菌酵素果凍(5入)',
                'price' => 300,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => '祕魯精品可可鈕扣',
                'price' => 600,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => '祕魯精品可可鈕扣精品盒',
                'price' => 800,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];
        Product::insert($products);
    }
}

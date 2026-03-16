<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {

        for ($i = 1; $i <= 5000; $i++) {

            Product::create([
                'name' => 'Product ' . $i,
                'price' => rand(50, 500)
            ]);

        }

    }
}
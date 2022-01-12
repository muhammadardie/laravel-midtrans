<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        \DB::table('products')->insert([
            'name' => 'Mobo ASUS H81M-K',
            'price' => 755000,
            'created_at' => now()
        ]);

        \DB::table('products')->insert([
            'name' => 'Mobo ASUS H61M-E',
            'price' => 649900,
            'created_at' => now()
        ]);

        \DB::table('products')->insert([
            'name' => 'Kingston Memory RAM DDR3 4GB',
            'price' => 175000,
            'created_at' => now()
        ]);

        \DB::table('products')->insert([
            'name' => 'Corsair Memory Ram DDR2 4GB',
            'price' => 130000,
            'created_at' => now()
        ]);       

        \DB::table('products')->insert([
            'name' => 'MSI NVIDIA GeForce GTX 1650 SUPER',
            'price' => 5200000,
            'created_at' => now()
        ]);       

        \DB::table('products')->insert([
            'name' => 'Gigabyte Radeon RX 560',
            'price' => 1735000,
            'created_at' => now()
        ]);       
    }
}

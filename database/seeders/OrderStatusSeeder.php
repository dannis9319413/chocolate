<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('order_statuses')->insert([
            ['name' => '未付款', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '已付款', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

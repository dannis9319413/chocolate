<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('distributors')->insert([
            ['name' => 'like841129', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '46sweetno1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'g__uava', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ren__0722', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Catalina', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

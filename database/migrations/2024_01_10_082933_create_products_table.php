<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('original_price');
            $table->integer('price');
            $table->string('image_path');
            $table->timestamps();
        });

        DB::table('products')->insert([
            [
                'name'  => '巧纖可可錠 鳳梨益生菌酵素果凍(5入)',
                'original_price' => 499,
                'price' => 300,
                'image_path' => '/images/product1.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => '秘魯精品可可禮盒',
                'original_price' => 998,
                'price' => 600,
                'image_path' => '/images/product2.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name'  => '巧孅與秘魯可可禮盒組合',
                'original_price' => 1497,
                'price' => 800,
                'image_path' => '/images/product3.png',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

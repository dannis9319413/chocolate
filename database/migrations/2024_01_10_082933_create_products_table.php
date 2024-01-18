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
            $table->integer('price');
            $table->timestamps();
        });

        DB::table('products')->insert([
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

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
        Schema::create('distributors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('distributors')->insert([
            ['name' => 'like841129', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '46sweetno1', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'g__uava', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ren__0722', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Catalina', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributors');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kunuunsas', function (Blueprint $table) {
            $table->id();
            $table->decimal('lafa_harkaan_aramame', 8, 2)->nullable();
        $table->decimal('lafa_keemikaalaan_aramame', 8, 2)->nullable();
        $table->decimal('hamma_keemikaalaa_litiraa', 8, 2)->nullable();
        $table->decimal('lafa_dhibeen_tooatame', 8, 2)->nullable();
        $table->decimal('lafa_ilbiisni_toatame', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunuunsas');
    }
};

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
        Schema::create('haamaas', function (Blueprint $table) {
            $table->id();
            $table->decimal('lafa_maashinaan_haamame', 8, 2)->nullable();
        $table->integer('maashina_haamaa_hirmaate')->nullable();

        $table->decimal('lafa_harkaan_haamame', 8, 2)->nullable();
        $table->integer('qotee_bulaa_dhi_haamame')->nullable();
        $table->integer('qotee_bulaa_du_haamame')->nullable();
        $table->integer('kan_tumame')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('haamaas');
    }
};

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
        Schema::create('facaasaas', function (Blueprint $table) {
            $table->id();
            $table->decimal('lafa_facae', 8, 2)->nullable();
            $table->string('mala_facaasaa')->nullable(); // e.g. harkaan, makiinaa
            $table->integer('qonnaan_bulaa_dhi')->nullable();
            $table->integer('qonnaan_bulaa_du')->nullable();
            $table->integer('qonnaan_bulaa_idaama')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facaasaas');
    }
};

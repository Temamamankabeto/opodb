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
        Schema::create('zonaldevisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('zone_id'); // MUST match crop_types.id
            $table->unsignedBigInteger('crop_id'); // MUST match crop_types.id
            
            $table->string('total_land'); 
            $table->string('totla_production'); 
            $table->string('ammount_of_land_for zone'); 
            $table->string('ammount_of_prucuction_for'); 
            $table->string('productivity'); 
           $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
           $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zonaldevisions');
    }
};

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
        Schema::create('kebeles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('woreda_id'); // MUST match crop_types.id
            $table->string('name'); 
            $table->string('total_area')->nullable(); 
            $table->string('total_farm_area')->nullable(); 
            $table->string('total_population')->nullable(); 
            $table->string('male')->nullable(); 
            $table->string('female')->nullable(); 
             
           $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kebeles');
    }
};

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
        Schema::create('village_resources', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');
            
            // Tractors in the village
            $table->integer('tractors_private_baseline')->nullable();
            $table->integer('tractors_private_plan')->nullable();
            $table->integer('tractors_association_baseline')->nullable();
            $table->integer('tractors_association_plan')->nullable();
            $table->integer('tractors_idaama_baseline')->nullable();
            $table->integer('tractors_idaama_plan')->nullable();
            
            // A combiner in the village
            $table->integer('combiner_private_baseline')->nullable();
            $table->integer('combiner_private_plan')->nullable();
            $table->integer('combiner_association_baseline')->nullable();
            $table->integer('combiner_association_plan')->nullable();
            $table->integer('combiner_idaama_baseline')->nullable();
            $table->integer('combiner_idaama_plan')->nullable();
            
            // Water pump/Motor in the village
            $table->integer('water_pump_private_baseline')->nullable();
            $table->integer('water_pump_private_plan')->nullable();
            $table->integer('water_pump_association_baseline')->nullable();
            $table->integer('water_pump_association_plan')->nullable();
            $table->integer('water_pump_idaama_baseline')->nullable();
            $table->integer('water_pump_idaama_plan')->nullable();
            
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate entries for same years
            $table->unique(['baseline_year', 'plan_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_resources');
    }
};

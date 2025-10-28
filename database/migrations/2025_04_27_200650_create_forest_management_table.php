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
        Schema::create('forest_management', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');
            
            // Forest Areas
            $table->decimal('forest_area_baseline', 10, 2)->nullable();
            $table->decimal('forest_area_plan', 10, 2)->nullable();
            $table->decimal('grazing_free_area_baseline', 10, 2)->nullable();
            $table->decimal('grazing_free_area_plan', 10, 2)->nullable();
            
            // Cooperatives
            $table->integer('cooperatives_baseline')->nullable();
            $table->integer('cooperatives_plan')->nullable();
            $table->integer('cooperative_members_baseline')->nullable();
            $table->integer('cooperative_members_plan')->nullable();
            
            // Waste Management
            $table->integer('dry_waste_areas_baseline')->nullable();
            $table->integer('dry_waste_areas_plan')->nullable();
            
            // Latrines/Toilets
            $table->integer('latrine_private_baseline')->nullable();
            $table->integer('latrine_private_plan')->nullable();
            $table->integer('latrine_community_baseline')->nullable();
            $table->integer('latrine_community_plan')->nullable();
            $table->integer('latrine_total_baseline')->nullable();
            $table->integer('latrine_total_plan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['baseline_year', 'plan_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forest_management');
    }
};

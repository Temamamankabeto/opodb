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
        Schema::create('construction_maintenances', function (Blueprint $table) {
            $table->id();$table->integer('baseline_year');
            $table->integer('plan_year');
            
            // Solar Center
            $table->decimal('solar_center_baseline', 12, 2)->nullable();
            $table->decimal('solar_center_plan', 12, 2)->nullable();
            
            // Biogas Construction
            $table->decimal('biogas_family_baseline', 12, 2)->nullable();
            $table->decimal('biogas_family_plan', 12, 2)->nullable();
            $table->decimal('biogas_community_baseline', 12, 2)->nullable();
            $table->decimal('biogas_community_plan', 12, 2)->nullable();
            $table->decimal('biogas_cost_baseline', 12, 2)->nullable();
            $table->decimal('biogas_cost_plan', 12, 2)->nullable();
            $table->decimal('biogas_idaama_baseline', 12, 2)->nullable();
            $table->decimal('biogas_idaama_plan', 12, 2)->nullable();
            
            // Electric Lines
            $table->decimal('electric_lines_baseline', 12, 2)->nullable();
            $table->decimal('electric_lines_plan', 12, 2)->nullable();
            
            // Rainfall Water Harvesting
            $table->decimal('rainfall_family_baseline', 12, 2)->nullable();
            $table->decimal('rainfall_family_plan', 12, 2)->nullable();
            $table->decimal('rainfall_community_baseline', 12, 2)->nullable();
            $table->decimal('rainfall_community_plan', 12, 2)->nullable();
            $table->decimal('rainfall_cost_baseline', 12, 2)->nullable();
            $table->decimal('rainfall_cost_plan', 12, 2)->nullable();
            $table->decimal('rainfall_idaama_baseline', 12, 2)->nullable();
            $table->decimal('rainfall_idaama_plan', 12, 2)->nullable();
            
            // Water Spring Development
            $table->decimal('water_spring_baseline', 12, 2)->nullable();
            $table->decimal('water_spring_plan', 12, 2)->nullable();
            
            // Hand Developed Well
            $table->decimal('well_family_baseline', 12, 2)->nullable();
            $table->decimal('well_family_plan', 12, 2)->nullable();
            $table->decimal('well_community_baseline', 12, 2)->nullable();
            $table->decimal('well_community_plan', 12, 2)->nullable();
            $table->decimal('well_cost_baseline', 12, 2)->nullable();
            $table->decimal('well_cost_plan', 12, 2)->nullable();
            $table->decimal('well_idaama_baseline', 12, 2)->nullable();
            $table->decimal('well_idaama_plan', 12, 2)->nullable();
            
            // Water Supply Maintenance
            $table->decimal('water_supply_maintenance_baseline', 12, 2)->nullable();
            $table->decimal('water_supply_maintenance_plan', 12, 2)->nullable();
            
            // New Road Construction
            $table->decimal('new_road_baseline', 12, 2)->nullable();
            $table->decimal('new_road_plan', 12, 2)->nullable();
            
            // Road Maintenance
            $table->decimal('road_maintenance_baseline', 12, 2)->nullable();
            $table->decimal('road_maintenance_plan', 12, 2)->nullable();
            
            // Water Furrow Construction
            $table->decimal('water_furrow_baseline', 12, 2)->nullable();
            $table->decimal('water_furrow_plan', 12, 2)->nullable();
            
            // Small Bridges Construction
            $table->decimal('small_bridges_baseline', 12, 2)->nullable();
            $table->decimal('small_bridges_plan', 12, 2)->nullable();
            
            $table->timestamps();
            $table->softDeletes();
        
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_maintenances');
    }
};

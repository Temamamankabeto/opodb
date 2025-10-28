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
        Schema::create('weekly_land_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('croptype_id')->constrained('crop_types')->onDelete('cascade');
            $table->foreignId('crop_id')->constrained('crops')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');
        
            $table->string('plan_year'); 
            $table->string('baseline_year'); 
            $table->string('land_area_plan_idaama'); 
            $table->string('weekly_achievement_percentage'); 
        
            $table->string('month'); // e.g., Meskerem or July
            $table->unsignedTinyInteger('week'); // 1 to 5
            $table->decimal('planned_value', 10, 2)->nullable();
            $table->decimal('achieved_value', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_land_plans');
    }
};

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
        Schema::create('agricultural_inputs', function (Blueprint $table) {
            $table->id();
            $table->integer('baseline_year');
            $table->integer('plan_year');
            
            // Chemical fertilizers [Tons]
            $table->decimal('chemical_fertilizer_ganna_baseline', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_ganna_plan', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_bona_baseline', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_bona_plan', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_arfaasaa_baseline', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_arfaasaa_plan', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_idaama_baseline', 10, 2)->nullable();
            $table->decimal('chemical_fertilizer_idaama_plan', 10, 2)->nullable();
            
            // Selective Seed [Tons]
            $table->decimal('selective_seed_ganna_baseline', 10, 2)->nullable();
            $table->decimal('selective_seed_ganna_plan', 10, 2)->nullable();
            $table->decimal('selective_seed_bona_baseline', 10, 2)->nullable();
            $table->decimal('selective_seed_bona_plan', 10, 2)->nullable();
            $table->decimal('selective_seed_arfaasaa_baseline', 10, 2)->nullable();
            $table->decimal('selective_seed_arfaasaa_plan', 10, 2)->nullable();
            $table->decimal('selective_seed_idaama_baseline', 10, 2)->nullable();
            $table->decimal('selective_seed_idaama_plan', 10, 2)->nullable();
            
            // Chemical pesticide and insecticide sprayers [Litre]
            $table->decimal('pesticide_ganna_baseline', 10, 2)->nullable();
            $table->decimal('pesticide_ganna_plan', 10, 2)->nullable();
            $table->decimal('pesticide_bona_baseline', 10, 2)->nullable();
            $table->decimal('pesticide_bona_plan', 10, 2)->nullable();
            $table->decimal('pesticide_arfaasaa_baseline', 10, 2)->nullable();
            $table->decimal('pesticide_arfaasaa_plan', 10, 2)->nullable();
            $table->decimal('pesticide_idaama_baseline', 10, 2)->nullable();
            $table->decimal('pesticide_idaama_plan', 10, 2)->nullable();
            
            // Spraying of pesticides and insecticides [Hectares of land]
            $table->decimal('spraying_ganna_baseline', 10, 2)->nullable();
            $table->decimal('spraying_ganna_plan', 10, 2)->nullable();
            $table->decimal('spraying_bona_baseline', 10, 2)->nullable();
            $table->decimal('spraying_bona_plan', 10, 2)->nullable();
            $table->decimal('spraying_arfaasaa_baseline', 10, 2)->nullable();
            $table->decimal('spraying_arfaasaa_plan', 10, 2)->nullable();
            $table->decimal('spraying_idaama_baseline', 10, 2)->nullable();
            $table->decimal('spraying_idaama_plan', 10, 2)->nullable();
            
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
        Schema::dropIfExists('agricultural_inputs');
    }
};

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
        Schema::create('yearly_crop_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');

            $table->string('plan_year'); 
            $table->string('baseline_year'); 
            $table->unsignedBigInteger('croptype_id')->nullable(); 
            $table->unsignedBigInteger('crop_id')->nullable(); 

            // plan 
            $table->string('land_area_plan_ganna')->nullable(); 
            $table->string('land_area_plan_bona')->nullable(); 
            $table->string('land_area_plan_arfaasaa')->nullable(); 

            $table->string('land_area_plan_idaama')->nullable(); 
             
            $table->string('production_plan_ganna')->nullable(); 
            $table->string('production_plan_bona')->nullable(); 
            $table->string('production_plan_arfaasaa')->nullable(); 

            $table->string('production_plan_idaama')->nullable(); 
                        //    productivity  plan
            $table->string('productivity_plan_ganna')->nullable(); 
            $table->string('productivity_plan_bona')->nullable(); 
            $table->string('productivity_plan_arfasa')->nullable(); 

            $table->string('productivity_plan_idaama')->nullable(); 


            // achievement  
            $table->string('land_area_baseline_ganna')->nullable(); 
            $table->string('land_area_baseline_bona')->nullable(); 
            $table->string('land_area_baseline_arfaasaa')->nullable(); 
            $table->string('land_area_baseline_idaama')->nullable(); 
            

            $table->string('production_baseline_ganna')->nullable(); 
            $table->string('production_baseline_bona')->nullable(); 
            $table->string('production_baseline_arfaasaa')->nullable(); 

            $table->string('production_baseline_idaama')->nullable();    
            

             //    productivity  plan
             $table->string('productivity_baseline_gana')->nullable(); 
             $table->string('productivity_baseline_bona')->nullable(); 
             $table->string('productivity_baseline_arfasa')->nullable(); 

             $table->string('productivity_baseline_idaama')->nullable(); 
 
            $table->string('performancy')->nullable(); 


            $table->string('created_by')->nullable(); 


                // relation 
                $table->foreign('croptype_id')->references('id')->on('crop_types')->onDelete('cascade');
                $table->foreign('crop_id')->references('id')->on('crops')->onDelete('cascade');

            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yearly_crop_plans');
    }
};

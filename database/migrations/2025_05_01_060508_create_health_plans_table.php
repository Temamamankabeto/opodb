<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('health_plans', function (Blueprint $table) {
            $table->id();

            $table->year('baseline_year');
            $table->year('plan_year');

            $table->integer('pregnant_followed_baseline')->nullable();
            $table->integer('pregnant_followed_plan')->nullable();

            $table->integer('institution_delivery_baseline')->nullable();
            $table->integer('institution_delivery_plan')->nullable();

            $table->integer('postpartum_followup_baseline')->nullable();
            $table->integer('postpartum_followup_plan')->nullable();

            $table->integer('balanced_food_support_baseline')->nullable();
            $table->integer('balanced_food_support_plan')->nullable();

            $table->integer('newborn_vaccination_followup_baseline')->nullable();
            $table->integer('newborn_vaccination_followup_plan')->nullable();

            $table->integer('under5_campaign_vaccine_baseline')->nullable();
            $table->integer('under5_campaign_vaccine_plan')->nullable();

            $table->integer('under5_diarrhea_baseline')->nullable();
            $table->integer('under5_diarrhea_plan')->nullable();

            $table->integer('malaria_site_cleaned_baseline')->nullable();
            $table->integer('malaria_site_cleaned_plan')->nullable();

            $table->integer('family_planning_service_baseline')->nullable();
            $table->integer('family_planning_service_plan')->nullable();

            $table->integer('community_insurance_spouses_baseline')->nullable();
            $table->integer('community_insurance_spouses_plan')->nullable();

            $table->integer('community_insurance_contribution_baseline')->nullable();
            $table->integer('community_insurance_contribution_plan')->nullable();

            $table->integer('child_development_centers_baseline')->nullable();
            $table->integer('child_development_centers_plan')->nullable();

            $table->integer('ecd_food_low_income_baseline')->nullable();
            $table->integer('ecd_food_low_income_plan')->nullable();

            $table->integer('ecd_centers_baseline')->nullable();
            $table->integer('ecd_centers_plan')->nullable();

            $table->string('idaama_baseline')->nullable();
            $table->string('idaama_plan')->nullable();

            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');
//            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_plans');
    }
};

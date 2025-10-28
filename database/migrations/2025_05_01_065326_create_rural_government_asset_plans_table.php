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
        Schema::create('rural_government_asset_plans', function (Blueprint $table) {
            $table->id();
            $table->year('baseline_year');
            $table->year('plan_year');

            // Buusaa Gonofaa
            $table->integer('bg_members_baseline')->nullable();
            $table->integer('bg_members_plan')->nullable();
            $table->decimal('bg_contributions_baseline', 12, 2)->nullable();
            $table->decimal('bg_contributions_plan', 12, 2)->nullable();
            $table->decimal('bg_crop_quintal_baseline', 12, 2)->nullable();
            $table->decimal('bg_crop_quintal_plan', 12, 2)->nullable();
            $table->decimal('bg_monetary_value_baseline', 12, 2)->nullable();
            $table->decimal('bg_monetary_value_plan', 12, 2)->nullable();
            $table->decimal('bg_cash_baseline', 12, 2)->nullable();
            $table->decimal('bg_cash_plan', 12, 2)->nullable();
            $table->string('bg_idaama_baseline')->nullable();
            $table->string('bg_idaama_plan')->nullable();

            // Gaachena Sirnaa
            $table->integer('gs_trained_baseline')->nullable();
            $table->integer('gs_trained_plan')->nullable();
            $table->integer('gs_assigned_daily_baseline')->nullable();
            $table->integer('gs_assigned_daily_plan')->nullable();

            // Citizenship service activities
            $table->decimal('citizenship_service_baseline', 12, 2)->nullable();
            $table->decimal('citizenship_service_plan', 12, 2)->nullable();

            // Mobilized family prosperity
            $table->decimal('family_prosperity_baseline', 12, 2)->nullable();
            $table->decimal('family_prosperity_plan', 12, 2)->nullable();

            // Loan taken
            $table->decimal('loan_not_paid_baseline', 12, 2)->nullable();
            $table->decimal('loan_not_paid_plan', 12, 2)->nullable();
            $table->decimal('loan_taken_this_year_baseline', 12, 2)->nullable();
            $table->decimal('loan_taken_this_year_plan', 12, 2)->nullable();
            $table->string('loan_idaama_baseline')->nullable();
            $table->string('loan_idaama_plan')->nullable();

            // Credit/loan paid
            $table->decimal('loan_paid_saving_baseline', 12, 2)->nullable();
            $table->decimal('loan_paid_saving_plan', 12, 2)->nullable();
            $table->decimal('loan_paid_coop_baseline', 12, 2)->nullable();
            $table->decimal('loan_paid_coop_plan', 12, 2)->nullable();
            $table->string('loan_paid_idaama_baseline')->nullable();
            $table->string('loan_paid_idaama_plan')->nullable();

            $table->unsignedBigInteger('zone_id')->nullable();
            $table->unsignedBigInteger('woreda_id')->nullable();
            $table->unsignedBigInteger('kebele_id')->nullable();
//            $table->unsignedBigInteger('user_id')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rural_government_asset_plans');
    }
};

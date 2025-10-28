<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rural_government_asset_plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // for tracking who created it
            $table->string('region')->nullable(); // Or use a region_id if it's a foreign key
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('woreda_id');
            $table->unsignedBigInteger('kebele_id');
            $table->year('plan_year');

            $table->integer('bg_members_plan')->nullable();
            $table->decimal('bg_contributions_plan', 12, 2)->nullable();
            $table->decimal('bg_crop_quintal_plan', 12, 2)->nullable();
            $table->decimal('bg_monetary_value_plan', 12, 2)->nullable();
            $table->decimal('bg_cash_plan', 12, 2)->nullable();
            $table->string('bg_idaama_plan')->nullable();

            $table->integer('gs_trained_plan')->nullable();
            $table->integer('gs_assigned_daily_plan')->nullable();

            $table->decimal('citizenship_service_plan', 12, 2)->nullable();
            $table->decimal('family_prosperity_plan', 12, 2)->nullable();

            $table->decimal('loan_not_paid_plan', 12, 2)->nullable();
            $table->decimal('loan_taken_this_year_plan', 12, 2)->nullable();
            $table->string('loan_idaama_plan')->nullable();

            $table->decimal('loan_paid_saving_plan', 12, 2)->nullable();
            $table->decimal('loan_paid_coop_plan', 12, 2)->nullable();
            $table->string('loan_paid_idaama_plan')->nullable();

// Add indexes or foreign keys if needed
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');
            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('cascade');
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

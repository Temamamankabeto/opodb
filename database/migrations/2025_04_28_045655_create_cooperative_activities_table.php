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
        Schema::create('cooperative_activities', function (Blueprint $table) {
            $table->id();$table->integer('baseline_year');
            $table->integer('plan_year');
            $table->integer('cooperatives_count_baseline')->nullable();
            $table->integer('cooperatives_count_plan')->nullable();
            // Members data
            $table->integer('members_baseline')->nullable();
            $table->integer('members_plan')->nullable();
            
            // Financial data
            $table->decimal('capitals_baseline', 15, 2)->nullable();
            $table->decimal('capitals_plan', 15, 2)->nullable();
            $table->decimal('credit_taken_baseline', 15, 2)->nullable();
            $table->decimal('credit_taken_plan', 15, 2)->nullable();
            $table->decimal('credit_repaid_baseline', 15, 2)->nullable();
            $table->decimal('credit_repaid_plan', 15, 2)->nullable();
            
            // Services data
            $table->integer('extension_services_baseline')->nullable();
            $table->integer('extension_services_plan')->nullable();
            
            // Organizational data
            $table->integer('union_membership_baseline')->nullable();
            $table->integer('union_membership_plan')->nullable();
            
            // Market participation
            $table->integer('export_market_baseline')->nullable();
            $table->integer('export_market_plan')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for better query performance
            $table->index(['baseline_year', 'plan_year']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cooperative_activities');
    }
};

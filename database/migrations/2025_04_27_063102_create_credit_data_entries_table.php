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
        Schema::create('credit_data_entries', function (Blueprint $table) {
            $table->id();
            $table->year('baseline_year');
            $table->year('plan_year');

            // Total debt
            $table->decimal('total_debt_baseline_private', 15, 2)->nullable();
            $table->decimal('total_debt_baseline_association', 15, 2)->nullable();
            $table->decimal('total_debt_baseline_total', 15, 2)->nullable();
            $table->decimal('total_debt_plan_private', 15, 2)->nullable();
            $table->decimal('total_debt_plan_association', 15, 2)->nullable();
            $table->decimal('total_debt_plan_total', 15, 2)->nullable();

            // Loans repaid
            $table->decimal('loans_repaid_baseline_private', 15, 2)->nullable();
            $table->decimal('loans_repaid_baseline_association', 15, 2)->nullable();
            $table->decimal('loans_repaid_baseline_total', 15, 2)->nullable();
            $table->decimal('loans_repaid_plan_private', 15, 2)->nullable();
            $table->decimal('loans_repaid_plan_association', 15, 2)->nullable();
            $table->decimal('loans_repaid_plan_total', 15, 2)->nullable();

            // Remaining debt
            $table->decimal('remaining_debt_baseline_private', 15, 2)->nullable();
            $table->decimal('remaining_debt_baseline_association', 15, 2)->nullable();
            $table->decimal('remaining_debt_plan_private', 15, 2)->nullable();
            $table->decimal('remaining_debt_plan_association', 15, 2)->nullable();

            // Savings made for mechanization
            $table->decimal('savings_baseline_association', 15, 2)->nullable();
            $table->decimal('savings_baseline_private', 15, 2)->nullable();
            $table->decimal('savings_baseline_total', 15, 2)->nullable();
            $table->decimal('savings_plan_association', 15, 2)->nullable();
            $table->decimal('savings_plan_private', 15, 2)->nullable();
            $table->decimal('savings_plan_total', 15, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_data_entries');
    }
};

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
        Schema::create('employment_opportunity_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->foreignId('job_opportunity_id')->constrained()->onDelete('cascade');
            $table->foreignId('enterprise_work_id')->constrained()->onDelete('cascade'); // from enterprise_works table
            $table->json('opportunity_data'); // Stores new opportunity data from job_opportunities table
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
$table->text('approval_description')->nullable();
$table->timestamp('approved_at')->nullable();
$table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employment_opportunity_plans');
    }
};

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
        Schema::create('cultural_plans', function (Blueprint $table) {
            $table->id();
             $table->string('year');
            $table->json('cultural_activities');// Store multiple cultural activities as JSON the activity is feached from CulturalRevival model along with unit then store the quantity planned for the year

            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');

            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cultural_plans');
    }
};

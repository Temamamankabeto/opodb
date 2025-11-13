<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('environmental_protection_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->json('environmental_data'); // Stores environmental activities data
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Unique constraint to prevent duplicate plans for same year, kebele, and sector
            $table->unique(['year', 'kebele_id', 'sector_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('environmental_protection_plans');
    }
};
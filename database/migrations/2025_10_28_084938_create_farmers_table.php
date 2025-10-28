<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('farmer_name');
            $table->enum('gender', ['Male', 'Female']);
            $table->string('phone_number')->nullable();
            $table->string('region')->nullable();

            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('union_id')->constrained()->onDelete('cascade');
            $table->foreignId('association_id')->constrained()->onDelete('cascade');

            $table->string('house_number')->nullable();
            $table->string('field_description')->nullable();
            $table->string('landholding_size')->nullable();
            $table->string('gps_coordinates')->nullable();

            $table->enum('irrigation_type', ['Rain-fed', 'Irrigated'])->nullable();
            $table->enum('soil_type', ['Clay', 'Loam', 'Sandy', 'Other'])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};

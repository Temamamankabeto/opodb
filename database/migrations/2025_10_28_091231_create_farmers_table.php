<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
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
            $table->text('field_description')->nullable();

            // JSON field for land types and related details
            $table->json('land_details')->nullable(); // stores [{land_type_id, size, gps_x, gps_y, soil_type, irrigation_type}, ...]
            $table->json('total_land')->nullable();  // stores aggregated land by type
            $table->json('land_type_details')->nullable();  // stores aggregated land by type
            $table->unsignedBigInteger('created_by'); // User ID who created the record
            

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};

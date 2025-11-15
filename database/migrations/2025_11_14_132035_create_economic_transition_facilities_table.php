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
        Schema::create('economic_transition_facilities', function (Blueprint $table) {
            $table->id();
              $table->string('year');
            $table->foreignId('institution_id')->constrained()->onDelete('cascade');
            $table->json('new_building_data'); // Stores new building data
            $table->json('improvement_data'); // Stores improvement data
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_transition_facilities');
    }
};

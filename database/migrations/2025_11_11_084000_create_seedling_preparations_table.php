<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seedling_preparations', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            
            // Separate JSON columns for preparation and planting data
            $table->json('preparation_data')->nullable()->comment('Data for seedling preparation');
            $table->json('planting_data')->nullable()->comment('Data for seedling planting');
            
            // Nursery information
            $table->decimal('baayyina_nersery', 10, 2)->nullable()->comment('Number of seedlings produced');
            $table->decimal('ballina_nersery', 10, 2)->nullable()->comment('Total Area of nursery in hectares');
            $table->decimal('nerserif_nama_hirmaate', 10, 2)->nullable()->comment('Number of participants in nursery');
            $table->decimal('nerserif_baayina_guyyaa', 10, 2)->nullable()->comment('Number of days in nursery');
            
            // Location fields
            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');    
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seedling_preparations');
    }
};
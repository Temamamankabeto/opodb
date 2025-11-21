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
        Schema::create('mechanizations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('mechanization_type')->nullable();
            $table->decimal('amount', 15, 2)->nullable(); // Changed to decimal for monetary values
            $table->string('unit')->nullable();
            $table->year('year'); // Changed to year type
            $table->json('loan_details')->nullable();
             $table->json('mechanization_distribution_details')->nullable()->after('loan_details');
             
            
            // Foreign keys
            $table->foreignId('sector_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mechanizations');
    }
};
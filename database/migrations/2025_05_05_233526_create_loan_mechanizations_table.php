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
        Schema::create('loan_mechanizations', function (Blueprint $table) {
            $table->id();
            // Location references
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            
            // Basic information
            $table->year('year');
            $table->string('loan_type', 50);
            
            // Quantity information
            $table->decimal('private_quantity', 12, 2)->default(0);
            $table->decimal('group_quantity', 12, 2)->default(0);
            $table->decimal('total_quantity', 12, 2)->default(0);
            $table->string('unit', 10)->default('ETB');
            
            // User information
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('phone', 15);
            
            // Timestamps
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index(['year', 'kebele_id']);
            $table->index('loan_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_mechanizations');
    }
};

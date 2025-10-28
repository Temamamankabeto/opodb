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
        Schema::create('material_supplies', function (Blueprint $table) {
            $table->id();

            $table->integer('year');
            $table->string('material_name');
            $table->decimal('private_qty', 10, 2);
            $table->decimal('group_qty', 10, 2);
            $table->decimal('total_qty', 10, 2);
            
            // Location fields
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            
            // User information
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_supplies');
    }
};

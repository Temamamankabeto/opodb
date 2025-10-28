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
        Schema::create('qabeenya_umamas', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->decimal('dherina_d_h', 10, 2);
            $table->decimal('ballina', 10, 2);
            $table->decimal('humna_nama', 10, 2);
            $table->decimal('turtii', 10, 2);
            
           
            $table->foreignId('kebele_id')->constrained('kebeles')->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained('woredas')->onDelete('cascade');
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('cascade');
        
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('phone')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qabeenya_umamas');
    }
};

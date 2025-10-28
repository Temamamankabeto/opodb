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
        Schema::create('misoma_beyladas', function (Blueprint $table) {
            $table->id();
           
            $table->string('year');
            $table->decimal('raaddeen', 10, 2);
            $table->decimal('lukkuu_cuucii', 10, 2);
            $table->decimal('lukkuu_sookee', 10, 2);
            $table->decimal('cuucii_qurxummii', 10, 2);
            $table->decimal('gaagura_hammayya', 10, 2);
            $table->decimal('lafa_omisha_beylada', 10, 2);
            $table->decimal('omisha_beylada', 10, 2);
           
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
        Schema::dropIfExists('misoma_beyladas');
    }
};

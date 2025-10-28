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
        Schema::create('misoma_beylada_productions', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->decimal('Jabbii_ai', 10, 2);
            $table->decimal('killee_oomishame', 10, 2);
            $table->decimal('lukkuu_foonii', 10, 2);
            $table->decimal('damma_oomishame', 10, 2);
            $table->decimal('qurxummii_gabaaf', 10, 2);
            $table->decimal('aannan_elmame', 10, 2);
           
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
        Schema::dropIfExists('misoma_beylada_productions');
    }
};

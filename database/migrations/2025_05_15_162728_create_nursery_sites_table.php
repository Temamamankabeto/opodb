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
        Schema::create('nursery_sites', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->decimal('baayyina', 10, 2);
            $table->decimal('ballina', 10, 2);
            $table->decimal('nama_hirmaate', 10, 2);
            $table->decimal('baayina_guyyaa', 10, 2);
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
      
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
        Schema::dropIfExists('nursery_sites');
    }
};

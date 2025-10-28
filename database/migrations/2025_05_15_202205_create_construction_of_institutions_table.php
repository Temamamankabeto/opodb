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
        Schema::create('construction_of_institutions', function (Blueprint $table) {
            $table->id();

            // Foreign keys for administrative hierarchy
            $table->unsignedBigInteger('zone_id');
            $table->unsignedBigInteger('woreda_id');
            $table->unsignedBigInteger('kebele_id')->nullable();  // Optional if not always used
            $table->unsignedBigInteger('sector_id')->nullable();  // Optional if not always used

            // Construction data
            $table->string('service_name');
            $table->enum('type', ['performance', 'plan']);
            $table->year('year');
            $table->integer('count')->default(0);
            $table->decimal('cost', 15, 2)->default(0);

            $table->timestamps();

            // Foreign key constraints (optional for soft relations)
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('woreda_id')->references('id')->on('woredas')->onDelete('cascade');
            $table->foreign('kebele_id')->references('id')->on('kebeles')->onDelete('set null');
            $table->foreign('sector_id')->references('id')->on('kebele_sectors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('construction_of_institutions');
    }
};

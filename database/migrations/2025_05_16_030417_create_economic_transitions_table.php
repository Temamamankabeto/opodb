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
        Schema::create('economic_transitions', function (Blueprint $table) {
            $table->id();

            // Year fields
            $table->string('amount');
            $table->year('plan_year');

            // Activity-related fields
            $table->string('activity_name'); // example: "ljaarsa man-kuusaa oomisha qonnaa"

            $table->integer('family')->default(0);
            $table->integer('cooperative')->default(0);
            $table->integer('community')->default(0);
            $table->integer('total')->default(0);

            // Cost field (optional for last row only)
            $table->decimal('construction_cost', 15, 2)->nullable();

            // Optional hierarchy (if needed)
            $table->foreignId('zone_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('woreda_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('kebele_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('sector_id')->nullable()->constrained('kebele_sectors')->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('econonomic_transitions');
    }
};

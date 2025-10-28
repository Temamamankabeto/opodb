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
        Schema::create('baballina_waldaas', function (Blueprint $table) {
            $table->id();
             $table->year('year'); // Ethiopian year
            $table->string('association_type');
            $table->unsignedInteger('association_number');
            $table->unsignedInteger('association_member');
            $table->decimal('capital', 15, 2)->nullable();
            $table->decimal('tooked_borrow', 15, 2)->nullable();
            $table->decimal('repair_borrow', 15, 2)->nullable();
             $table->string('region');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('baballina_waldaas');
    }
};

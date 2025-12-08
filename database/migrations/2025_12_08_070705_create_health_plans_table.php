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
        Schema::create('health_plans', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->json('health_activity_name'); // Example field for health activity names(activity name is feached from health table, then it saved as ajeson along with ammount and budjet for that year, remember all activity name unit is saved in health table)

            $table->foreignId('sector_id')->constrained('kebele_sectors')->onDelete('cascade');

            $table->foreignId('kebele_id')->constrained()->onDelete('cascade');
            $table->foreignId('woreda_id')->constrained()->onDelete('cascade');
            $table->foreignId('zone_id')->constrained()->onDelete('cascade');
            $table->foreignId('region_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_plans');
    }
};

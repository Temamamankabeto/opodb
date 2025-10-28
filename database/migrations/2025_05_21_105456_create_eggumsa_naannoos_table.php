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
        Schema::create('eggumsa_naannoos', function (Blueprint $table) {
            $table->id();
            $table->year('year');
            $table->decimal('limited_forest_land', 10, 2);
            $table->decimal('land_free_from_contact', 10, 2);
            $table->integer('associations_organized');
            $table->integer('number_of_members');
            $table->integer('dry_waste_sites');
            $table->integer('private_bathroom');
            $table->integer('community_toilets');
            $table->integer('sum_bathroom')->virtualAs('private_bathroom + community_toilets');
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
        Schema::dropIfExists('eggumsa_naannoos');
    }
};

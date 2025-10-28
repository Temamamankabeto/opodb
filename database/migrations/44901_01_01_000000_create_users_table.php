<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->nullable();

            $table->foreignId('region_id')->nullable()->constrained('regions')->onDelete('set null');
            $table->foreignId('zone_id')->nullable()->constrained('zones')->onDelete('set null');
            $table->foreignId('woreda_id')->nullable()->constrained('woredas')->onDelete('set null');
            $table->foreignId('kebele_id')->nullable()->constrained('kebeles')->onDelete('set null');

            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null'); // assuming you have a roles table
            $table->foreignId('sector_id')->nullable()->constrained('kebele_sectors')->onDelete('set null'); // assuming you have a roles table

            $table->string('employee_id')->nullable(); // Employee or internal system ID
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // user status

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();

            // Optional: For soft deleting users without losing their record
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

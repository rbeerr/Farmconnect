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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('role'); // Update role to accept Farm-Owner and Farm-Worker
            $table->string('firstName')->nullable(); // Add firstName column
            $table->string('lastName')->nullable(); // Add lastName column
            $table->string('contactNumber')->nullable(); // Add contactNumber column
            $table->date('dateOfBirth')->nullable(); // Add dateOfBirth column
            $table->string('province')->nullable(); // Add province column
            $table->string('municipality')->nullable(); // Add municipality column
            $table->string('barangay')->nullable(); // Add barangay column
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

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
        Schema::create('profile_role', function (Blueprint $table) {
            // Foreign key to the profiles table
            $table->foreignId('profile_id')
                  ->constrained() // Assumes the linked table is 'profiles'
                  ->onDelete('cascade'); // If a profile is deleted, its role assignments are deleted

            // Foreign key to the roles table
            $table->foreignId('role_id')
                  ->constrained() // Assumes the linked table is 'roles'
                  ->onDelete('cascade'); // If a role is deleted, its assignments are deleted

            // Set the combination of profile_id and role_id as the primary key.
            // This prevents a profile from having the same role more than once.
            $table->primary(['profile_id', 'role_id']);

            // No need for 'timestamps' on a basic pivot table.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_role');
    }
};

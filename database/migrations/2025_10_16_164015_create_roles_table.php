<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // For inserting default data

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Display name (e.g., 'Lead Pastor')
            $table->string('slug')->unique(); // Machine name (e.g., 'lead_pastor')
            $table->timestamps();
        });

        // Insert initial roles required by your business logic
        DB::table('roles')->insert([
            ['name' => 'Member', 'slug' => 'member', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Staff', 'slug' => 'staff', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Board', 'slug' => 'board', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Volunteer', 'slug' => 'volunteer', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Ministry Leader', 'slug' => 'ministry_leader', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lead Pastor', 'slug' => 'lead_pastor', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};

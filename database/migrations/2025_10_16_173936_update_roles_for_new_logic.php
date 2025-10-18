<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Define the new roles needed for the Staff and Volunteer tiers
        $newRoles = [
            ['name' => 'Admin', 'slug' => 'admin'],
            ['name' => 'Associate Pastor', 'slug' => 'associate_pastor'],
            ['name' => 'Church Advisor', 'slug' => 'church_advisor'],
            ['name' => 'Ministry Assistant', 'slug' => 'ministry_assistant'],
            ['name' => 'Pastor-In-Training', 'slug' => 'pastor_in_training'],
            ['name' => 'Team Member', 'slug' => 'team_member'],
        ];

        $records = [];
        foreach ($newRoles as $role) {
            // Check if the role already exists to prevent duplicate key errors
            if (!DB::table('roles')->where('slug', $role['slug'])->exists()) {
                $records[] = array_merge($role, [
                    'created_at' => now(), 
                    'updated_at' => now()
                ]);
            }
        }
        
        if (!empty($records)) {
            DB::table('roles')->insert($records);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Define the slugs of the roles to be removed
        $roleSlugsToRemove = [
            'admin', 
            'associate_pastor', 
            'church_advisor', 
            'ministry_assistant', 
            'pastor_in_training', 
            'team_member'
        ];

        // Delete the records from the roles table
        DB::table('roles')->whereIn('slug', $roleSlugsToRemove)->delete();
    }
};
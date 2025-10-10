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
        Schema::dropIfExists('profiles');

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->text('street_address')->nullable();
            $table->text('city_address')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->string('email_address')->nullable()->unique();
            $table->date('birthday')->nullable();
            $table->string('gender')->nullable(); 
            $table->string('marital_status')->nullable();
            $table->date('joined_date')->nullable()->comment('The day they started attending New Life Tagaytay.');
            $table->date('baptism_date')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('added_by_id');
            $table->unsignedBigInteger('last_updated_by_id')->nullable();
            
            $table->foreign('added_by_id')->references('id')->on('users');
            $table->foreign('last_updated_by_id')->references('id')->on('users');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

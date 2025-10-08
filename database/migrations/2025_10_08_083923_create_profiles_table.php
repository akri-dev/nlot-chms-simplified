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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            // Personal Information
            $table->string('first_name', 100);
            $table->string('middle_name', 100)->nullable();
            $table->string('last_name', 100);
            $table->text('address')->nullable();
            $table->string('contact_number', 20)->nullable();
            $table->date('birthday')->nullable();
            $table->enum('gender', ['Male', 'Female'])->nullable();

            // Marriage Information
            // This field is nullable, assuming not everyone is married.
            $table->date('anniversary_date')->nullable()->comment('Date of marriage anniversary, if married.');

            // Organization-Specific Information
            $table->date('joined_date')->nullable()->comment('The day they started attending New Life Tagaytay.');
            $table->timestamps();
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

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
        Schema::create('marriages', function (Blueprint $table) {
            $table->id();

            // Foreign key for the husband's profile
            $table->foreignId('husband_id')
                ->constrained('profiles')
                ->onDelete('cascade');

            // Foreign key for the wife's profile
            $table->foreignId('wife_id')
                ->constrained('profiles')
                ->onDelete('cascade');

            // The key piece of data you need
            $table->date('anniversary_date');

            // Ensures one profile can't be linked as a husband more than once
            $table->unique('husband_id');
            // Ensures one profile can't be linked as a wife more than once
            $table->unique('wife_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marriages');
    }
};

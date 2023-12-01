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
        Schema::create('pet_has_tag', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::table('pet_has_tag', function(Blueprint $table)
        {
            $table->foreignId('pet_id')->constrained();
            $table->foreignId('tag_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pet_has_tag');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up(): void
    {
        Schema::create('laravel_model_note_table', function (Blueprint $table) {
            $table->id();

            // add fields

            $table->timestamps();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down(): void
    {
        Schema::dropIfExists('laravel_model_note_table');
    }
};

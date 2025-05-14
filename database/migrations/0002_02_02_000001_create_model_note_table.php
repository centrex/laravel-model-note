<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $connection = config('laravel-model-note.drivers.database.connection', config('database.default'));

        Schema::connection($connection)->create('model_notes', function (Blueprint $table) {
            $table->id();
            $table->morphs('model');
            $table->string('tag')->default('note');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('is_private')->default(0);
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $connection = config('laravel-model-note.drivers.database.connection', config('database.default'));

        Schema::connection($connection)->dropIfExists('model_notes');
    }
};

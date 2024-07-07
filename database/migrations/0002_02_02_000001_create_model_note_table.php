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
        Schema::create('model_notes', function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->string('tag')->nullable();
            $table->boolean('is_private')->default(0);
            $table->unsignedBigInteger('user_id')->nullable();
            $table->morphs('model');
            $table->timestamps();

            $table->index(['user_id', 'model_id', 'model_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('model_notes');
    }
};

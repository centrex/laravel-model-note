<?php

declare(strict_types = 1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration for creating the model notes table.
 *
 * Sets up database structure for polymorphic notes with:
 * - Flexible tagging system
 * - Privacy controls
 * - User attribution
 * - Full audit capabilities
 */
return new class() extends Migration
{
    /**
     * Get the database connection name.
     */
    protected function getConnection(): string
    {
        return config('laravel-model-note.drivers.database.connection')
            ?? config('database.default');
    }

    public function up(): void
    {
        Schema::connection($this->getConnection())->create('model_notes', function (Blueprint $table) {
            // Recommended for proper Unicode support (emojis, special characters)
            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            $table->id();

            // Polymorphic relationship (consider using uuid instead if needed)
            $table->string('model_type');
            $table->unsignedBigInteger('model_id');
            // Alternative for UUID support:
            // $table->uuid('model_id');

            // Note metadata
            $table->string('tag', 50)->default('general')->index();
            $table->unsignedBigInteger('user_id')
                ->nullable()
                ->comment('User who created the note');

            // Recommended foreign key (if users table exists)
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();

            $table->boolean('is_private')->default(false);
            $table->text('note')->nullable();

            // Recommended for content search (if your DB supports it)
            // $table->fullText(['note'])
            //     ->language(config('app.locale', 'english'));

            // Add status tracking if notes need approval:
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected'])
                ->default('approved')
                ->index();

            // Timestamps with microseconds precision if needed
            $table->timestamps(6);

            // Recommended for data recovery
            // $table->softDeletes('deleted_at', 6);

            // Recommended indexes for common query patterns
            $table->index(
                ['model_type', 'model_id', 'deleted_at'],
                'model_notes_model_with_trashed_index',
            );

            $table->index(
                ['user_id', 'created_at'],
                'model_notes_user_recency_index',
            );

            $table->index(
                ['tag', 'is_private', 'created_at'],
                'model_notes_tag_privacy_recency_index',
            );
        });

        // Recommended for large tables to maintain performance
        DB::statement('ALTER TABLE model_notes PARTITION BY HASH(id) PARTITIONS 10');
    }

    public function down(): void
    {
        Schema::connection($this->getConnection())->dropIfExists('model_notes');

        // Recommended if using partitioning
        DB::statement('ALTER TABLE model_notes REMOVE PARTITIONING');
    }
};

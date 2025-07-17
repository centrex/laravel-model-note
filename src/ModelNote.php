<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Represents a note attached to any model via polymorphic relationship.
 *
 * This model stores notes with their content, privacy status, tags,
 * and relationships to both the target model and the user who created the note.
 */
class ModelNote extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'model_notes';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_private' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Initialize the model with proper database connection.
     *
     * @param  array  $attributes  Model attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setConnection(
            config('laravel-model-note.drivers.database.connection') ?? config('database.default'),
        );
    }

    /**
     * Define the polymorphic relationship to the parent model.
     *
     * @return MorphTo The relationship instance
     */
    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the string representation of the note.
     *
     * @return string The note content
     */
    public function __toString(): string
    {
        return (string) $this->note;
    }

    /**
     * Get human-readable time since creation (e.g., "2 hours ago").
     *
     * @return string The formatted time difference or empty string if no timestamp
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at?->diffForHumans() ?? '';
    }

    /**
     * Scope for filtering private notes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrivate($query)
    {
        return $query->where('is_private', true);
    }

    /**
     * Scope for filtering public notes.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePublic($query)
    {
        return $query->where('is_private', false);
    }

    /**
     * Scope for filtering notes by tag.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $tag  The tag to filter by
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTag($query, string $tag)
    {
        return $query->where('tag', $tag);
    }
}

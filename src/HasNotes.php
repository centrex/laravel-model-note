<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;

/**
 * Provides note-taking functionality to Eloquent models.
 *
 * This trait enables models to have polymorphic relationships with notes,
 * supporting features like tagging, privacy control, and bulk operations.
 */
trait HasNotes
{
    /**
     * Defines the polymorphic relationship to notes.
     *
     * @return MorphMany The relationship query builder ordered by latest notes first
     */
    public function notes(): MorphMany
    {
        return $this->morphMany(
            $this->getNoteModelClassName(),  // The note model class from config
            'model',                        // The polymorphic relation name
            'model_type',                  // The column storing model type
            $this->getModelColumnName(),    // The column storing model ID
        )->latest('id');                   // Orders by newest notes first
    }

    /**
     * Shortcut accessor for the most recent note's content.
     *
     * @return string|null The note content or null if no note exists
     */
    public function note(): ?string
    {
        return $this->lastNote()?->note;
    }

    /**
     * Adds a new note to the model.
     *
     * @param  string  $note  The note content
     * @param  bool  $isPrivate  Marks note as private if true
     * @param  string|null  $tag  Optional categorization tag
     * @param  int|null  $userId  Optional user ID (defaults to current authenticated user)
     * @return static Returns the model instance for method chaining
     */
    public function addNote(
        string $note,
        bool $isPrivate = false,
        ?string $tag = null,
        ?int $userId = null,
    ): static {
        $this->notes()->create([
            'note'       => $note,
            'tag'        => $tag,
            'is_private' => $isPrivate,
            'user_id'    => $userId ?? auth()->id(),  // Fallback to current user
        ]);

        return $this;
    }

    /**
     * Shortcut method to add a private note.
     *
     * @param  string  $note  The note content
     * @param  string|null  $tag  Optional categorization tag
     * @return static Returns the model instance for method chaining
     */
    public function addPrivateNote(string $note, ?string $tag = null): static
    {
        return $this->addNote($note, true, $tag);
    }

    /**
     * Retrieves the most recent note, optionally filtered by tags.
     *
     * @param  string|array  ...$tags  Variadic tags to filter by
     * @return mixed The note model instance or null
     */
    public function lastNote(string|array ...$tags): mixed
    {
        $tags = $this->normalizeTags($tags);
        $query = $this->relationLoaded('notes') ? $this->notes : $this->notes();

        return $tags
            ? $query->whereIn('tag', $tags)->first()
            : $query->first();
    }

    /**
     * Gets all notes associated with the model, optionally filtered by tags.
     *
     * @param  string|array  ...$tags  Variadic tags to filter by
     * @return \Illuminate\Database\Eloquent\Collection Collection of notes
     */
    public function allNotes(string|array ...$tags): \Illuminate\Database\Eloquent\Collection
    {
        $tags = $this->normalizeTags($tags);
        $query = $this->relationLoaded('notes') ? $this->notes : $this->notes();

        return $tags
            ? $query->whereIn('tag', $tags)->get()
            : $query->get();
    }

    /**
     * Gets only private notes, optionally filtered by tags.
     *
     * @param  string|array  ...$tags  Variadic tags to filter by
     * @return \Illuminate\Database\Eloquent\Collection Collection of private notes
     */
    public function privateNotes(string|array ...$tags): \Illuminate\Database\Eloquent\Collection
    {
        $tags = $this->normalizeTags($tags);
        $query = $this->relationLoaded('notes') ? $this->notes : $this->notes();

        $query = $query->where('is_private', true);

        return $tags
            ? $query->whereIn('tag', $tags)->get()
            : $query->get();
    }

    /**
     * Deletes specific notes by their IDs.
     *
     * @param  int|array  ...$ids  Variadic note IDs to delete
     * @return static Returns the model instance for method chaining
     */
    public function deleteNote(int|array ...$ids): static
    {
        $ids = Arr::flatten($ids);

        if (!empty($ids)) {
            $this->notes()->whereIn('id', $ids)->delete();
        }

        return $this;
    }

    /**
     * Deletes notes matching specific tags.
     *
     * @param  string|array  ...$tags  Variadic tags to match for deletion
     * @return static Returns the model instance for method chaining
     */
    public function deleteNoteByTag(string|array ...$tags): static
    {
        $tags = $this->normalizeTags($tags);

        if (!empty($tags)) {
            $this->notes()->whereIn('tag', $tags)->delete();
        }

        return $this;
    }

    /**
     * Deletes all notes associated with the model.
     *
     * @return static Returns the model instance for method chaining
     */
    public function deleteAllNotes(): static
    {
        $this->notes()->delete();

        return $this;
    }

    /**
     * Gets the note model's table name.
     *
     * @return string The note model's table name
     */
    protected function getNoteTableName(): string
    {
        return app($this->getNoteModelClassName())->getTable();
    }

    /**
     * Gets the model's foreign key column name from config.
     *
     * @return string The configured column name or default 'model_id'
     */
    protected function getModelColumnName(): string
    {
        return config('laravel-model-note.model_primary_key_attribute', 'model_id');
    }

    /**
     * Gets the note model class name from config.
     *
     * @return string The fully qualified note model class name
     */
    protected function getNoteModelClassName(): string
    {
        return config('laravel-model-note.note_model');
    }

    /**
     * Normalizes and filters tag parameters.
     *
     * @param  array  $tags  Input tags (may be nested arrays from variadic parameters)
     * @return array Flat array of non-empty tags
     */
    protected function normalizeTags(array $tags): array
    {
        $flattened = Arr::flatten($tags);

        return array_filter($flattened, fn ($tag): bool => !empty($tag));
    }
}

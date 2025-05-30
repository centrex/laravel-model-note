<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote;

use Illuminate\Database\Eloquent\Relations\{MorphMany, Relation};
use Illuminate\Support\Arr;

trait HasNotes
{
    public function notes(): MorphMany
    {
        return $this->morphMany($this->getNoteModelClassName(), 'model', 'model_type', $this->getModelColumnName())
            ->latest('id');
    }

    public function note()
    {
        return $this->lastNote();
    }

    public function addNote(string $note, ?bool $is_private = false, ?string $tag = null, ?int $user_id = null): self
    {
        $this->notes()->create([
            'note'       => $note,
            'tag'        => $tag,
            'is_private' => $is_private,
            'user_id'    => $user_id,
        ]);

        return $this;
    }

    public function addPrivateNote(string $note, ?string $tag = null): self
    {
        $this->notes()->create([
            'note'       => $note,
            'tag'        => $tag,
            'is_private' => true,
            'user_id'    => auth()->user()->id,
        ]);

        return $this;
    }

    public function lastNote(...$tags)
    {
        $notes = $this->relationLoaded('notes') ? $this->notes : $this->notes();

        $tags = is_array($tags) ? Arr::flatten($tags) : func_get_args();

        if (count($tags) < 1) {
            return $notes->first();
        }

        return $notes->whereIn('tag', $tags)->first();
    }

    public function allNotes(...$tags)
    {
        $notes = $this->relationLoaded('notes') ? $this->notes : $this->notes();

        $tags = is_array($tags) ? Arr::flatten($tags) : func_get_args();

        if (count($tags) < 1) {
            return $notes->get();
        }

        return $notes->whereIn('tag', $tags)->get();
    }

    public function privateNotes(...$tags)
    {
        $notes = $this->relationLoaded('notes') ? $this->notes : $this->notes();

        $tags = is_array($tags) ? Arr::flatten($tags) : func_get_args();

        if (count($tags) < 1) {
            return $notes->get();
        }

        return $notes->where('is_private', 1)->whereIn('tag', $tags)->get();
    }

    public function deleteNote(...$ides)
    {
        $ides = is_array($ides) ? Arr::flatten($ides) : func_get_args();

        if (count($ides) < 1) {
            return $this;
        }

        return $this->notes()->whereIn('id', $ides)->delete();
    }

    public function deleteNoteByTag(...$tags)
    {
        $tags = is_array($tags) ? Arr::flatten($tags) : func_get_args();

        if (count($tags) < 1) {
            return $this;
        }

        return $this->notes()->whereIn('tag', $tags)->delete();
    }

    public function deleteAllNotes()
    {
        return $this->notes()->delete();
    }

    public function getNoteAttribute(): string
    {
        return (string) optional($this->lastNote())->note;
    }

    protected function getNoteTableName(): string
    {
        $modelClass = $this->getNoteModelClassName();

        return (new $modelClass())->getTable();
    }

    protected function getModelColumnName(): string
    {
        return config('laravel-model-note.model_primary_key_attribute') ?? 'model_id';
    }

    protected function getNoteModelClassName(): string
    {
        return config('laravel-model-note.note_model');
    }

    protected function getNoteModelType(): string
    {
        return array_search(static::class, Relation::morphMap()) ?: static::class;
    }
}

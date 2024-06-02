<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelNote extends Model
{
    protected $guarded = [];

    protected $table = 'model_notes';

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString(): string
    {
        return (string) $this->name;
    }
}

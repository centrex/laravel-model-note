<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelNote extends Model
{
    protected $guarded = [];

    protected $table = 'model_notes';

    /**
     * Specify the connection, since this implements multitenant solution
     * Called via constructor to faciliate testing
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setConnection(config('laravel-model-note.drivers.database.connection'), config('database.default'));
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }

    public function __toString(): string
    {
        return (string) $this->note;
    }

    public function getTimeAgoAttribute() 
    {
        return $this->created_at? $this->created_at->diffForHumans() : '';
    }
}

<?php

declare(strict_types = 1);

namespace Centrex\LaravelModelNote\Events;

use Centrex\LaravelModelNote\ModelNote;

class NoteAdded {

    public $note;

    public function __construct(ModelNote $note) {
        $this->note = $note;
    }
}